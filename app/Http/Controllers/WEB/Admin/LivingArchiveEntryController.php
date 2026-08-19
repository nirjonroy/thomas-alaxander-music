<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLivingArchiveEntryRequest;
use App\Http\Requests\Admin\UpdateLivingArchiveEntryRequest;
use App\Models\LivingArchiveEntry;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Str;

class LivingArchiveEntryController extends Controller
{
    public function index()
    {
        $entries = LivingArchiveEntry::withCount('children')->ordered()->get();
        $archiveRows = $this->flattenEntries($entries);

        return view('admin.living_archive_entries.index', compact('archiveRows'));
    }

    public function create()
    {
        $parentOptions = $this->flattenEntries(LivingArchiveEntry::ordered()->get());
        $entry = new LivingArchiveEntry([
            'status' => true,
            'page_type' => 'archive_page',
            'sort_order' => 0,
        ]);

        return view('admin.living_archive_entries.create', compact('entry', 'parentOptions'));
    }

    public function store(StoreLivingArchiveEntryRequest $request)
    {
        $data = $this->preparedData($request);

        $entry = new LivingArchiveEntry($data);
        $this->applyUploads($entry, $request);
        $entry->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.living-archive-entry.index')->with($notification);
    }

    public function edit(LivingArchiveEntry $living_archive_entry)
    {
        $entry = $living_archive_entry;
        $parentOptions = $this->flattenEntries(
            LivingArchiveEntry::where('id', '!=', $entry->id)->ordered()->get()
        )->reject(fn ($row) => $this->isDescendantOf($row['entry'], $entry));

        return view('admin.living_archive_entries.edit', compact('entry', 'parentOptions'));
    }

    public function update(UpdateLivingArchiveEntryRequest $request, LivingArchiveEntry $living_archive_entry)
    {
        $entry = $living_archive_entry;
        $data = $this->preparedData($request, $entry);

        $entry->fill($data);
        $this->applyUploads($entry, $request);
        $this->applyRemovals($entry, $request);
        $entry->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.living-archive-entry.index')->with($notification);
    }

    public function destroy(LivingArchiveEntry $living_archive_entry)
    {
        $entry = $living_archive_entry->loadCount('children');

        if ($entry->children_count > 0) {
            $notification = 'Reassign or delete child pages before deleting this parent archive page.';
            $notification = ['messege' => $notification, 'alert-type' => 'warning'];

            return redirect()->back()->with($notification);
        }

        foreach (['featured_image', 'document_image', 'og_image'] as $field) {
            $this->deleteUpload($entry->{$field});
        }

        $entry->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function changeStatus($id)
    {
        $entry = LivingArchiveEntry::findOrFail($id);

        if ($entry->status) {
            $entry->status = false;
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $entry->status = true;
            $message = trans('admin_validation.Active Successfully');
        }

        $entry->save();

        return response()->json($message);
    }

    private function preparedData(Request $request, ?LivingArchiveEntry $entry = null): array
    {
        $data = $request->validated();
        $data['parent_id'] = $data['parent_id'] ?? null;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['status'] = (bool) $data['status'];
        $data['published_at'] = $data['published_at'] ?? null;

        unset(
            $data['featured_image'],
            $data['document_image'],
            $data['og_image'],
            $data['remove_featured_image'],
            $data['remove_document_image'],
            $data['remove_og_image']
        );

        $rawSlug = trim((string) ($request->input('slug') ?: ($entry?->slug ?: $request->input('title'))));
        $data['slug'] = $this->uniqueSlug(Str::slug($rawSlug), $entry?->id);

        return $data;
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $baseSlug = $slug ?: 'archive-page';
        $candidate = $baseSlug;
        $counter = 2;

        while (
            LivingArchiveEntry::where('slug', $candidate)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    private function applyUploads(LivingArchiveEntry $entry, Request $request): void
    {
        foreach (['featured_image', 'document_image', 'og_image'] as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $oldFile = $entry->{$field};
            $entry->{$field} = $this->storeUpload($request->file($field), $field);
            $this->deleteUpload($oldFile);
        }
    }

    private function applyRemovals(LivingArchiveEntry $entry, Request $request): void
    {
        $removals = [
            'remove_featured_image' => 'featured_image',
            'remove_document_image' => 'document_image',
            'remove_og_image' => 'og_image',
        ];

        foreach ($removals as $input => $field) {
            if (! $request->boolean($input) || $request->hasFile($field)) {
                continue;
            }

            $this->deleteUpload($entry->{$field});
            $entry->{$field} = null;
        }
    }

    private function storeUpload($file, string $field): string
    {
        $directory = public_path('uploads/living-archive');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = $field.'-'.date('Y-m-d-h-i-s').'-'.rand(999, 9999).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/living-archive/'.$filename;
    }

    private function deleteUpload(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath) && File::isFile($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function flattenEntries(Collection $entries, ?int $parentId = null, int $depth = 0): Collection
    {
        return $entries
            ->where('parent_id', $parentId)
            ->values()
            ->flatMap(function (LivingArchiveEntry $entry) use ($entries, $depth) {
                return collect([['entry' => $entry, 'depth' => $depth]])
                    ->merge($this->flattenEntries($entries, $entry->id, $depth + 1));
            });
    }

    private function isDescendantOf(LivingArchiveEntry $candidate, LivingArchiveEntry $entry): bool
    {
        $parent = $candidate->parent;

        while ($parent) {
            if ((int) $parent->id === (int) $entry->id) {
                return true;
            }

            $parent = $parent->parent;
        }

        return false;
    }
}

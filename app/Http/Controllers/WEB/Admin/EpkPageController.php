<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEpkPageRequest;
use App\Http\Requests\Admin\UpdateEpkPageRequest;
use App\Models\EpkPage;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EpkPageController extends Controller
{
    public function index()
    {
        $epkPages = EpkPage::ordered()->get();

        return view('admin.epk_pages.index', compact('epkPages'));
    }

    public function create()
    {
        $epkPage = new EpkPage([
            'status' => true,
            'sort_order' => 0,
            'sections' => [],
        ]);

        return view('admin.epk_pages.create', compact('epkPage'));
    }

    public function store(StoreEpkPageRequest $request)
    {
        $data = $this->preparedData($request);
        $epkPage = new EpkPage($data);
        $this->applyUploads($epkPage, $request);
        $epkPage->save();

        $notification = ['messege' => trans('admin_validation.Created Successfully'), 'alert-type' => 'success'];

        return redirect()->route('admin.epk-page.index')->with($notification);
    }

    public function edit(EpkPage $epk_page)
    {
        $epkPage = $epk_page;

        return view('admin.epk_pages.edit', compact('epkPage'));
    }

    public function show(EpkPage $epk_page)
    {
        return redirect()->route('admin.epk-page.edit', $epk_page->id);
    }

    public function update(UpdateEpkPageRequest $request, EpkPage $epk_page)
    {
        $epkPage = $epk_page;
        $epkPage->fill($this->preparedData($request, $epkPage));
        $this->applyUploads($epkPage, $request);
        $this->applyRemovals($epkPage, $request);
        $epkPage->save();

        $notification = ['messege' => trans('admin_validation.Updated Successfully'), 'alert-type' => 'success'];

        return redirect()->route('admin.epk-page.index')->with($notification);
    }

    public function destroy(EpkPage $epk_page)
    {
        foreach (['hero_image', 'gold_feather_image', 'og_image', 'audio_url'] as $field) {
            $this->deleteUpload($epk_page->{$field});
        }

        $epk_page->delete();

        $notification = ['messege' => trans('admin_validation.Delete Successfully'), 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function changeStatus($id)
    {
        $epkPage = EpkPage::findOrFail($id);
        $epkPage->status = ! $epkPage->status;
        $epkPage->save();

        $message = $epkPage->status
            ? trans('admin_validation.Active Successfully')
            : trans('admin_validation.Inactive Successfully');

        return response()->json($message);
    }

    private function preparedData(Request $request, ?EpkPage $epkPage = null): array
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['status'] = (bool) $data['status'];
        $data['published_at'] = $data['published_at'] ?? null;
        $data['sections'] = $this->sectionData($request);
        $data['overview_content'] = $this->stripScripts((string) ($data['overview_content'] ?? ''));

        unset(
            $data['hero_image'],
            $data['gold_feather_image'],
            $data['og_image'],
            $data['audio_file'],
            $data['remove_hero_image'],
            $data['remove_gold_feather_image'],
            $data['remove_og_image'],
            $data['remove_audio_file'],
            $data['lane_titles'],
            $data['lane_bodies'],
            $data['engagement_items'],
            $data['repertoire_items'],
            $data['testimonial_sources'],
            $data['testimonial_credentials'],
            $data['testimonial_quotes'],
            $data['audio_caption'],
            $data['video_intro'],
            $data['booking_body'],
            $data['section_titles'],
            $data['section_bodies']
        );

        if (
            $epkPage
            && ! $request->filled('audio_url')
            && ! $request->hasFile('audio_file')
            && ! $request->boolean('remove_audio_file')
        ) {
            unset($data['audio_url']);
        }

        $rawSlug = trim((string) ($request->input('slug') ?: ($epkPage?->slug ?: $request->input('title'))));
        $data['slug'] = $this->uniqueSlug(Str::slug($rawSlug), $epkPage?->id);

        return $data;
    }

    private function sectionData(Request $request): array
    {
        $slug = Str::slug((string) ($request->input('slug') ?: $request->input('title')));

        if ($slug === 'full-artist') {
            return $this->fullArtistSections($request);
        }

        if ($slug === 'crooners') {
            return $this->croonersSections($request);
        }

        $titles = $request->input('section_titles', []);
        $bodies = $request->input('section_bodies', []);
        $sections = [];

        foreach ($titles as $index => $title) {
            $title = trim((string) $title);
            $body = trim((string) ($bodies[$index] ?? ''));

            if ($title === '' && $body === '') {
                continue;
            }

            $sections[] = [
                'title' => $title,
                'body' => $this->stripScripts($body),
            ];
        }

        return $sections;
    }

    private function fullArtistSections(Request $request): array
    {
        return array_values(array_filter([
            [
                'type' => 'performance_lanes',
                'title' => 'Performance Lanes',
                'items' => $this->pairedItems($request->input('lane_titles', []), $request->input('lane_bodies', [])),
            ],
            [
                'type' => 'engagements',
                'title' => 'Notable Engagements',
                'items' => $this->listItems($request->input('engagement_items', [])),
            ],
            [
                'type' => 'tags',
                'title' => 'Featured Repertoire',
                'items' => $this->listItems($request->input('repertoire_items', [])),
            ],
            [
                'type' => 'testimonials',
                'title' => 'Testimonials',
                'items' => $this->testimonialItems($request),
            ],
            [
                'type' => 'medley',
                'title' => 'Live Performance Medley',
                'body' => $this->stripScripts((string) $request->input('audio_caption')),
            ],
            [
                'type' => 'booking',
                'title' => 'Booking & Representation',
                'body' => $this->stripScripts((string) $request->input('booking_body')),
            ],
        ], fn ($section) => ! empty($section['body']) || ! empty($section['items'])));
    }

    private function croonersSections(Request $request): array
    {
        return array_values(array_filter([
            [
                'type' => 'tags',
                'title' => 'Featured Selections',
                'items' => $this->listItems($request->input('repertoire_items', [])),
            ],
            [
                'type' => 'testimonials',
                'title' => 'Testimonials',
                'items' => $this->testimonialItems($request),
            ],
            [
                'type' => 'video',
                'title' => 'Live Performance',
                'body' => $this->stripScripts((string) $request->input('video_intro')),
                'url' => trim((string) $request->input('video_url')),
                'video_title' => trim((string) $request->input('video_title')),
            ],
            [
                'type' => 'booking',
                'title' => 'Booking & Contact',
                'body' => $this->stripScripts((string) $request->input('booking_body')),
            ],
        ], fn ($section) => ! empty($section['body']) || ! empty($section['items']) || ! empty($section['url'])));
    }

    private function pairedItems(array $titles, array $bodies): array
    {
        $items = [];

        foreach ($titles as $index => $title) {
            $title = trim((string) $title);
            $body = trim((string) ($bodies[$index] ?? ''));

            if ($title === '' && $body === '') {
                continue;
            }

            $items[] = ['title' => $title, 'body' => $body];
        }

        return $items;
    }

    private function listItems(array $items): array
    {
        return array_values(array_unique(array_filter(array_map(
            fn ($item) => trim((string) $item),
            $items
        ), fn ($item) => $item !== '')));
    }

    private function testimonialItems(Request $request): array
    {
        $sources = $request->input('testimonial_sources', []);
        $credentials = $request->input('testimonial_credentials', []);
        $quotes = $request->input('testimonial_quotes', []);
        $items = [];

        foreach ($sources as $index => $source) {
            $source = trim((string) $source);
            $credential = trim((string) ($credentials[$index] ?? ''));
            $quote = trim((string) ($quotes[$index] ?? ''));

            if ($source === '' && $quote === '') {
                continue;
            }

            $items[] = [
                'source' => $source,
                'credential' => $credential,
                'quote' => $quote,
            ];
        }

        return $items;
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $baseSlug = $slug ?: 'epk-page';
        $candidate = $baseSlug;
        $counter = 2;

        while (
            EpkPage::where('slug', $candidate)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    private function applyUploads(EpkPage $epkPage, Request $request): void
    {
        foreach (['hero_image', 'gold_feather_image', 'og_image'] as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $oldFile = $epkPage->{$field};
            $epkPage->{$field} = $this->storeUpload($request->file($field), $field);
            $this->deleteUpload($oldFile);
        }

        if ($request->hasFile('audio_file')) {
            $oldFile = $epkPage->audio_url;
            $epkPage->audio_url = $this->storeUpload($request->file('audio_file'), 'audio');
            $this->deleteUpload($oldFile);
        }
    }

    private function applyRemovals(EpkPage $epkPage, Request $request): void
    {
        $removals = [
            'remove_hero_image' => ['field' => 'hero_image', 'file_input' => 'hero_image'],
            'remove_gold_feather_image' => ['field' => 'gold_feather_image', 'file_input' => 'gold_feather_image'],
            'remove_og_image' => ['field' => 'og_image', 'file_input' => 'og_image'],
            'remove_audio_file' => ['field' => 'audio_url', 'file_input' => 'audio_file'],
        ];

        foreach ($removals as $input => $config) {
            if (! $request->boolean($input) || $request->hasFile($config['file_input'])) {
                continue;
            }

            $field = $config['field'];
            $this->deleteUpload($epkPage->{$field});
            $epkPage->{$field} = null;
        }
    }

    private function storeUpload($file, string $field): string
    {
        $directory = public_path('uploads/epk');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = $field.'-'.date('Y-m-d-h-i-s').'-'.rand(999, 9999).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/epk/'.$filename;
    }

    private function deleteUpload(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://']) || ! Str::startsWith($path, 'uploads/epk/')) {
            return;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath) && File::isFile($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function stripScripts(string $value): string
    {
        $value = preg_replace('/<\s*script\b[^>]*>.*?<\s*\/\s*script\s*>/is', '', $value) ?? '';

        return preg_replace('/\son\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]*)/i', '', $value) ?? '';
    }
}

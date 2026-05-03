<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use File;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('productcategory.index')) {
            abort(403, 'Unauthorized action.');
        }

        $events = Event::latest()->get();

        return view('admin.events', compact('events'));
    }

    public function create()
    {
        if (!auth()->user()->can('product-category-create')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.create_event');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('productCategory.store')) {
            abort(403, 'Unauthorized action.');
        }

        $this->normalizeSlug($request);
        $this->validate($request, $this->rules(), $this->validationMessages());

        $event = new Event();
        $this->saveEvent($request, $event);

        $notification = trans('admin_validation.Created Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.event.index')->with($notification);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('productCategory.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $event = Event::findOrFail($id);

        return view('admin.edit_event', compact('event'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('productCategory.update')) {
            abort(403, 'Unauthorized action.');
        }

        $event = Event::findOrFail($id);

        $this->normalizeSlug($request);
        $this->validate($request, $this->rules($event->id), $this->validationMessages());

        $this->saveEvent($request, $event);

        $notification = trans('admin_validation.Update Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.event.index')->with($notification);
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('productCategory.delete')) {
            abort(403, 'Unauthorized action.');
        }

        $event = Event::findOrFail($id);
        $this->deleteEventImages($event->image);
        $this->deleteFile($event->meta_image);
        $event->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.event.index')->with($notification);
    }

    public function changeStatus($id)
    {
        if (!auth()->user()->can('productCategory.changeStatus')) {
            abort(403, 'Unauthorized action.');
        }

        $event = Event::findOrFail($id);
        if ($event->status == 1) {
            $event->status = 0;
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $event->status = 1;
            $message = trans('admin_validation.Active Successfully');
        }

        $event->save();

        return response()->json($message);
    }

    private function rules(?int $eventId = null): array
    {
        $slugRule = 'required|string|max:255|unique:events,slug';

        if ($eventId) {
            $slugRule .= ','.$eventId;
        }

        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'name' => 'required|string|max:255',
            'slug' => $slugRule,
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'ticket_price' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'meta_image' => 'nullable|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'date.required' => trans('admin_validation.Date is required'),
            'time.required' => trans('admin_validation.Time is required'),
            'location.required' => trans('admin_validation.Location is required'),
        ];
    }

    private function normalizeSlug(Request $request): void
    {
        $request->merge([
            'slug' => Str::slug($request->slug ?: $request->name),
        ]);
    }

    private function saveEvent(Request $request, Event $event): void
    {
        if ($request->hasFile('image')) {
            $this->deleteEventImages($event->image);
            $event->image = $this->uploadEventImage($request);
        }

        if ($request->hasFile('meta_image')) {
            $this->deleteFile($event->meta_image);
            $event->meta_image = $this->uploadMetaImage($request);
        }

        $event->name = $request->name;
        $event->slug = $request->slug;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->location = $request->location;
        $event->ticket_price = $request->ticket_price;
        $event->description = $request->description;
        $event->status = $request->status;
        $event->seo_title = $request->seo_title ?: $request->name;
        $event->seo_description = $request->seo_description ?: $request->name;
        $event->seo_keywords = $request->seo_keywords;
        $event->seo_author = $request->seo_author;
        $event->seo_publisher = $request->seo_publisher;
        $event->canonical_url = $request->canonical_url;
        $event->meta_title = $request->meta_title;
        $event->meta_description = $request->meta_description;
        $event->meta_copyright = $request->meta_copyright;
        $event->site_name = $request->site_name;
        $event->save();
    }

    private function uploadEventImage(Request $request): string
    {
        $image = Image::make($request->file('image'));
        $extension = $request->file('image')->getClientOriginalExtension();
        $imageName = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999, 9999).'.'.$extension;

        $image->resize(800, 800);
        $image->save(public_path('uploads/custom-images/'.$imageName));

        $image->resize(300, 300);
        $image->save(public_path('uploads/custom-images2/'.$imageName));

        return $imageName;
    }

    private function uploadMetaImage(Request $request): string
    {
        $file = $request->file('meta_image');
        $extension = $file->getClientOriginalExtension();
        $imageName = 'event-meta-'.date('-Y-m-d-h-i-s-').rand(999, 9999).'.'.$extension;
        $path = 'uploads/website-images/'.$imageName;

        if (strtolower($extension) === 'svg') {
            $file->move(public_path('uploads/website-images'), $imageName);
        } else {
            Image::make($file)->save(public_path($path));
        }

        return $path;
    }

    private function deleteEventImages(?string $image): void
    {
        if (!$image) {
            return;
        }

        $this->deleteFile('uploads/custom-images/'.$image);
        $this->deleteFile('uploads/custom-images2/'.$image);
        $this->deleteFile('uploads/main-image/'.$image);
    }

    private function deleteFile(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}

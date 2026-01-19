<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Image;
use File;
use Str;

class VideoController extends Controller
{
    public function index()
    {
        
        $videos = Video::latest()->get();
        return view('admin.videos', compact('videos'));
    }

    public function create()
    {
        
        return view('admin.create_video');
    }

    public function store(Request $request)
    {
        

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
        ];

        $customMessages = [];
        $this->validate($request, $rules, $customMessages);

        $video = new Video();

        if ($request->hasFile('thumbnail')) {
            $image = Image::make($request->file('thumbnail'));
            $extension = $request->thumbnail->getClientOriginalExtension();
            $image_name = Str::slug($request->title).date('-Y-m-d-h-i-s-').rand(1000,9999).'.'.$extension;

            $destination_path = 'uploads/video-thumbnails/'.$image_name;
            $image->resize(800, 450); // 16:9
            $image->save(public_path().'/'.$destination_path);

            $video->thumbnail = $image_name;
        }

        $video->title = $request->title;
        $video->description = $request->description;
        $video->url = $request->url;
        $video->status = $request->status;
        $video->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];
        return redirect()->route('admin.video.index')->with($notification);
    }

    public function edit($id)
    {
        

        $video = Video::find($id);
        return view('admin.edit_video', compact('video'));
    }

    public function update(Request $request, $id)
    {
        

        $video = Video::find($id);

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
        ];

        $customMessages = [];
        $this->validate($request, $rules, $customMessages);

        if ($request->hasFile('thumbnail')) {
            $old_thumbnail = $video->thumbnail;
            $image = Image::make($request->file('thumbnail'));
            $extension = $request->thumbnail->getClientOriginalExtension();
            $image_name = Str::slug($request->title).date('-Y-m-d-h-i-s-').rand(1000,9999).'.'.$extension;

            $destination_path = 'uploads/video-thumbnails/'.$image_name;
            $image->resize(800, 450);
            $image->save(public_path().'/'.$destination_path);

            $video->thumbnail = $image_name;

            if ($old_thumbnail) {
                $old_path = public_path().'/uploads/video-thumbnails/'.$old_thumbnail;
                if (File::exists($old_path)) unlink($old_path);
            }
        }

        $video->title = $request->title;
        $video->description = $request->description;
        $video->url = $request->url;
        $video->status = $request->status;
        $video->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];
        return redirect()->route('admin.video.index')->with($notification);
    }

    public function destroy($id)
    {
        

        $video = Video::find($id);

        if ($video->thumbnail) {
            $path = public_path().'/uploads/video-thumbnails/'.$video->thumbnail;
            if (File::exists($path)) unlink($path);
        }

        $video->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = ['messege' => $notification, 'alert-type' => 'success'];
        return redirect()->route('admin.video.index')->with($notification);
    }

    public function changeStatus($id)
    {
        

        $video = Video::find($id);
        $video->status = !$video->status;
        $video->save();

        $message = $video->status
            ? trans('admin_validation.Active Successfully')
            : trans('admin_validation.Inactive Successfully');
        return response()->json($message);
    }
}

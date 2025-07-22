<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

use Image;
use File;
use Str;
class EventController extends Controller
{
    public function index()
    {

        if(!auth()->user()->can('productcategory.index')){
            abort(403, 'Unauthorized action.');
        }
        $events = Event::latest()->get();


        return view('admin.events',compact('events'));

    }


    public function create()
    {
      if(!auth()->user()->can('product-category-create')){
            abort(403, 'Unauthorized action.');
        }

        return view('admin.create_event');
    }


    public function store(Request $request)
    {

       if(!auth()->user()->can('productCategory.store')){
            abort(403, 'Unauthorized action.');
        }

        $rules = [
            'name'=>'',
          
        ];
        $customMessages = [
          

        ];
        $this->validate($request, $rules,$customMessages);

        $event = new Event();


      	if($request->image){
            $image = Image::make($request->file('image'));
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;


            $destation_path1 = 'uploads/custom-images/'.$image_name;
            $image->resize(800,800);
            $image->save(public_path().'/'.$destation_path1);

            // For Main Image
            $destation_path = 'uploads/custom-images2/'.$image_name;
            $image->resize(300,300);
            $image->save(public_path().'/'.$destation_path);
            //$product->thumb_image=$image_name;
          	$event->image = $image_name;
        }

        $event->name = $request->name;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->location = $request->location;
      	$event->ticket_price = $request->ticket_price;
        $event->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.event.index')->with($notification);
    }


    // public function show($id){
    //   	if(!auth()->user()->can('productCategory.show')){
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $category = Category::find($id);
    //     return response()->json(['category' => $category],200);
    // }

    public function edit($id)
    {
      if(!auth()->user()->can('productCategory.edit')){
            abort(403, 'Unauthorized action.');
        }

        $event = Event::find($id);
        return view('admin.edit_event',compact('event'));
    }


    public function update(Request $request,$id)
    {
      if(!auth()->user()->can('productCategory.update')){
            abort(403, 'Unauthorized action.');
        }

        $event = Event::find($id);
        $rules = [
            'name'=>'',
           
        ];

        $customMessages = [
        

        ];
        $this->validate($request, $rules,$customMessages);

        if($request->image){
            $old_thumbnail = $event->image;
            $image = Image::make($request->file('image'));
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;

            $destation_path1 = 'uploads/custom-images/'.$image_name;
            $image->resize(800,800);
            $image->save(public_path().'/'.$destation_path1);

            $destation_path2 = 'uploads/custom-images2/'.$image_name;
            $image->resize(300,300);
            $image->save(public_path().'/'.$destation_path2);

            $event->image=$image_name;
            $event->save();
            if($old_thumbnail){
                if(File::exists(public_path().'/uploads/custom-images/'.$old_thumbnail))unlink(public_path().'/uploads/custom-images/'.$old_thumbnail);
                if(File::exists(public_path().'/uploads/main-image/'.$old_thumbnail))unlink(public_path().'/uploads/main-image/'.$old_thumbnail);
            }
        }




        $event->name = $request->name;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->location = $request->location;
      	$event->ticket_price = $request->ticket_price;
        $event->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.event.index')->with($notification);
    }

    public function destroy($id)
    {
      if(!auth()->user()->can('productCategory.delete')){
            abort(403, 'Unauthorized action.');
        }

        $event = Event::find($id);
        $event->delete();
        

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.event.index')->with($notification);
    }

    public function changeStatus($id){
      if(!auth()->user()->can('productCategory.changeStatus')){
            abort(403, 'Unauthorized action.');
        }

        $category = Event::find($id);
        if($category->status==1){
            $category->status=0;
            $category->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $category->status=1;
            $category->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class SliderController extends Controller
{
     public function AllSlider(){

        $slider = Slider::find(1);
        return view('admin.backend.slider.update_slider',compact('slider'));

    } // End Method

/*
    public function AddSlider(){

        return view('admin.backend.slider.add_slider');

    } // End Method */


    /* public function StoreSlider(Request $request){

        if ($request->file('image')) {
            $image = $request->file('image');

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(1920,1027);
            $img->toJpeg(100)->save(base_path('public/upload/slider/'.$name_gen));
            $save_url = 'upload/slider/'.$name_gen;

            Slider::insert([
                'heading' => $request->heading,
                'short_desc' => $request->short_desc,
                'video' => $request->video,
                'image' => $save_url,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Slider Inserted Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('all.slider')->with($notification);

        }

    } // End Method */


   /*  public function EditSlider($id){

        $slider = Slider::find($id);
        return view('admin.backend.slider.edit_slider',compact('slider'));

    } // End Method */

    public function UpdateSlider(Request $request){

        $slider_id = $request->id;
        $old_img = $request->old_img;

        if ($request->file('image')) {
            $image = $request->file('image');

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(1920,1027);
            $img->toJpeg(100)->save(base_path('public/upload/slider/'.$name_gen));
            $save_url = 'upload/slider/'.$name_gen;

            if (file_exists($old_img)) {
                unlink($old_img);
            }

            Slider::find($slider_id)->update([
                'heading' => $request->heading,
                'short_desc' => $request->short_desc,
                'video' => $request->video,
                'image' => $save_url,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Slider Updated Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('all.slider')->with($notification);

        }else{
            Slider::find($slider_id)->update([
                'heading' => $request->heading,
                'short_desc' => $request->short_desc,
                'video' => $request->video,
                'created_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Slider Updated Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('all.slider')->with($notification);
        }

    } // End Method


   /*  public function DeleteSlider($id){

        $slider = Slider::find($id);
        $img = $slider->image;
        unlink($img);

        Slider::find($id)->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method */


}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Coursegoal;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function AllCourse(){

        $id = Auth::user()->id;
        $course = Course::where('instructor_id',$id)->orderBy('id','desc')->get();

        return view('instructor.course.all_course',compact('course'));

    } // End Mehtod


    public function AddCourse(){

        $categories = Category::latest()->get();
        return view('instructor.course.add_course',compact('categories'));

    } // End Mehtod


    public function GetSubcategory($category_id){

        $subcat = SubCategory::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
        return json_encode($subcat);

    } // End Mehtod


    public function StoreCourse(Request $request){

        $request->validate([
            'video' => 'required|mimes:mp4,webm|max:10000',
            'course_image' => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($request->file('course_image')) {
            $image = $request->file('course_image');

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(370,246);
            $img->save(base_path('public/upload/course/thumbnail/'.$name_gen));
            $save_url = 'upload/course/thumbnail/'.$name_gen;

        }

        if ($request->file('video')) {
            $video = $request->file('video');

            $manager = new ImageManager(new Driver());
            $videoName = time().'.'.$video->getClientOriginalExtension();
            $img = $manager->read($video);
            $img->save(base_path('public/upload/course/video/'.$videoName));
            $save_url = 'upload/course/video/'.$videoName;

        }

    } // End Mehtod


}

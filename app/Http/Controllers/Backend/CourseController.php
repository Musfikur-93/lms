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
use Carbon\Carbon;

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
            'video' => 'required|mimes:mp4|max:10000',
            'course_image' => 'required|mimes:png,jpg,jpeg',
        ]);


            $image = $request->file('course_image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(370,246);
            $img->save(base_path('public/upload/course/thumbnail/'.$name_gen));
            $save_url = 'upload/course/thumbnail/'.$name_gen;

            $video = $request->file('video');
            $videoName = time().'.'.$video->getClientOriginalExtension();
            $video->move(public_path('upload/course/video/'),$videoName);
            $save_video = 'upload/course/video/'.$videoName;


            $course_id = Course::insertGetId([
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'instructor_id' => Auth::user()->id,
                'course_image' => $save_url,
                'course_name' => $request->course_name,
                'course_name_slug' => strtolower(str_replace(' ','-',$request->course_name)),
                'course_title' => $request->course_title,
                'description' => $request->description,
                'video' => $save_video,

                'label' => $request->label,
                'duration' => $request->duration,
                'resources' => $request->resources,
                'certificate' => $request->certificate,
                'selling_price' => $request->selling_price,
                'discount_price' => $request->discount_price,

                'prerequisites' => $request->prerequisites,
                'bestseller' => $request->bestseller,
                'feartured' => $request->featured,
                'highestrated' => $request->highestrated,
                'status' => 1,
                'created_at' => Carbon::now(),

            ]);

            // Course Goal Add Form
            $goals = Count($request->course_goals);

            if ($goals != NULL) {
                for ($i=0; $i < $goals; $i++) {
                    $gcount = new Coursegoal();
                    $gcount->course_id = $course_id;
                    $gcount->goal_name = $request->course_goals[$i];
                    $gcount->save();
                }
            }
            // Course Goal Add Form

            $notification = array(
                'message' => 'Course Inserted Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('all.course')->with($notification);


    } // End Mehtod


    public function EditCourse($id){

        $course = Course::find($id);
        $goals = Coursegoal::where('course_id',$id)->get();
        $categories = Category::latest()->get();
        $subcategories = SubCategory::latest()->get();

        return view('instructor.course.edit_course',compact('course','categories','subcategories','goals'));

    } // End Mehtod


    public function UpdateCourse(Request $request){

            $cid = $request->course_id;

            Course::find($cid)->update([
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'instructor_id' => Auth::user()->id,
                'course_name' => $request->course_name,
                'course_name_slug' => strtolower(str_replace(' ','-',$request->course_name)),
                'course_title' => $request->course_title,
                'description' => $request->description,

                'label' => $request->label,
                'duration' => $request->duration,
                'resources' => $request->resources,
                'certificate' => $request->certificate,
                'selling_price' => $request->selling_price,
                'discount_price' => $request->discount_price,

                'prerequisites' => $request->prerequisites,
                'bestseller' => $request->bestseller,
                'feartured' => $request->featured,
                'highestrated' => $request->highestrated,

            ]);

            $notification = array(
                'message' => 'Course Updated Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('all.course')->with($notification);


    } // End Mehtod


    public function UpdateCourseImage(Request $request){

        $course_id = $request->id;
        $oldimg = $request->old_img;

        $image = $request->file('course_image');
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $img = $manager->read($image);
        $img = $img->resize(370,246);
        $img->save(base_path('public/upload/course/thumbnail/'.$name_gen));
        $save_url = 'upload/course/thumbnail/'.$name_gen;

        if ($oldimg) {
            unlink($oldimg);
        }

        Course::find($course_id)->update([
            'course_image' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Image Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);


    } // End Mehtod


    public function UpdateCourseVideo(Request $request){

        $course_id = $request->vid;
        $oldVideo = $request->old_vid;

        $video = $request->file('video');
        $videoName = time().'.'.$video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoName);
        $save_video = 'upload/course/video/'.$videoName;

        if (file_exists($oldVideo)) {
            unlink($oldVideo);
        }

        Course::find($course_id)->update([
            'video' => $save_video,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Video Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method


}

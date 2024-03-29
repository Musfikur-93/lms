<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
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

    } // End Method


    public function UpdateCourseGoal(Request $request){

        $cid = $request->id;

        if ($request->course_goals == NULL) {
            return redirect()->back();
        }else{
            Coursegoal::where('course_id',$cid)->delete();

            $goals = Count($request->course_goals);
            for ($i=0; $i < $goals; $i++) {
                $gcount = new Coursegoal();
                $gcount->course_id = $cid;
                $gcount->goal_name = $request->course_goals[$i];
                $gcount->save();

            } // end for

        } // end else

        $notification = array(
            'message' => 'Course Goals Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method


    public function DeleteCourse($id){

        $course = Course::find($id);
        unlink($course->course_image);
        unlink($course->video);

        Course::find($id)->delete();

        $goalData = Coursegoal::where('course_id',$id)->get();
        foreach ($goalData as $item) {
            $item->goal_name;
            Coursegoal::where('course_id',$id)->delete();
        }

        $notification = array(
            'message' => 'Course Goals Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method


    ////////////////  Course Lecture ///////////////

    public function AddCourseLecture($id){

        $course = Course::find($id);
        $section = CourseSection::where('course_id',$id)->latest()->get();

        return view('instructor.course.section.add_course_lecture',compact('course','section'));

    } // End Method


    public function AddCourseSection(Request $request){

        $cid = $request->id;

        CourseSection::insert([
            'course_id' => $cid,
            'section_title' => $request->section_title,
        ]);

        $notification = array(
            'message' => 'Course Section Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method


    public function SaveLecture(Request $request){

        $lecture = new CourseLecture();
        $lecture->course_id = $request->course_id;
        $lecture->section_id = $request->section_id;
        $lecture->lecture_title = $request->lecture_title;
        $lecture->url = $request->lecture_url;
        $lecture->content = $request->content;
        $lecture->save();

        return response()->json(['success' => 'Lecture Saved Successfully']);

    } // End Method


    public function EditLecture($id){

        $clecture = CourseLecture::find($id);
        return view('instructor.course.lecture.edit_course_lecture',compact('clecture'));

    } // End Method


    public function UpdateCourseLecture(Request $request){

        $lid = $request->id;

        CourseLecture::find($lid)->update([
            'lecture_title' => $request->lecture_title,
            'url' => $request->url,
            'content' => $request->content,
        ]);

        $notification = array(
            'message' => 'Course Lecture Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method


    public function DeleteLecture($id){

        CourseLecture::find($id)->delete();

        $notification = array(
            'message' => 'Course Lecture Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method


    public function DeleteSection($id){

        $section = CourseSection::find($id);

        //Delete Section Related Lecture
        $section->lectures()->delete();

        // Delete the total section
        $section->delete();

        $notification = array(
            'message' => 'Course Section Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    } // End Method


}

<?php

namespace App\Http\Controllers\Frontend;

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

class IndexController extends Controller
{
    public function CourseDetails($id, $slug){

        $course = Course::find($id);
        $goals = Coursegoal::where('course_id',$id)->orderBy('id','DESC')->get();

        $ins_id = $course->instructor_id;
        $instructorCourses = Course::where('instructor_id',$ins_id)->orderBy('id','DESC')->get();

        $category = Category::latest()->get();

        $cat_id = $course->category_id;
        $relatedCourse = Course::where('category_id',$cat_id)->where('id','!=', $id)->orderBy('id','DESC')->limit(3)->get();

        return view('frontend.course.course_details',compact('course','goals','instructorCourses','category','relatedCourse'));

    } // End Method


    public function CategoryCourse($id,$slug){

        $courses = Course::where('category_id',$id)->where('status',1)->get();
        $category = Category::where('id',$id)->first();
        $categories = Category::latest()->get();
        return view('frontend.category.category_all',compact('courses','category','categories'));

    } // End Method


    public function SubCategoryCourse($id,$slug){

        $courses = Course::where('subcategory_id',$id)->where('status',1)->get();
        $subcategory = SubCategory::where('id',$id)->first();
        $categories = Category::latest()->get();
        return view('frontend.category.subcategory_all',compact('courses','subcategory','categories'));

    } // End Method



}

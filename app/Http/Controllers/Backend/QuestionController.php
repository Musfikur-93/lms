<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseLecture;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuestionController extends Controller
{
    public function UserQuestion(Request $request){

        $course_id = $request->course_id;
        $instructor_id = $request->instructor_id;

        Question::insert([
            'course_id' => $course_id,
            'instructor_id' => $instructor_id,
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'queston' => $request->question,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Message Send Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method


    /////////////// Instructor all Questions //////////////

    public function InstructorAllQuestion(){

        $id = Auth::user()->id;
        $question = Question::where('instructor_id',$id)->where('parent_id',null)->orderBy('id','desc')->get();

        return view('instructor.question.all_question',compact('question'));

    } // End Method


    public function QuestionDetails($id){

        $question = Question::find($id);
        $reply = Question::where('parent_id',$id)->orderBy('id','desc')->get();

        return view('instructor.question.question_details',compact('question','reply'));

    } // End Method


    public function InstructorReplay(Request $request){

        $que_id = $request->qid;
        $user_id = $request->user_id;
        $instructor_id = $request->instructor_id;
        $course_id = $request->course_id;

        Question::insert([
            'course_id' => $course_id,
            'instructor_id' => $instructor_id,
            'user_id' => $user_id,
            'parent_id' => $que_id,
            'queston' => $request->queston,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Message Send Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('instructor.all.question')->with($notification);

    } // End Method


}

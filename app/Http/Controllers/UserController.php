<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function Index(){

        return view('frontend.index');

    } // End Method


    public function UserProfile(){

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('frontend.dashboard.edit_profile',compact('profileData'));

    } // End Method


    public function UserProfileUpdate(Request $request){

        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method


    public function UserLogout(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info',
        );

        return redirect('/login')->with($notification);

    } // End Method

    public function UserChangePassword(){

        return view('frontend.dashboard.change_password');

    } // End Method


    public function UserPasswordUpdate(Request $request){

        /// validation
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);

            if (!Hash::check($request->old_password, auth::user()->password)) {
                $notification = array(
                    'message' => 'Old Password Does Not Match!',
                    'alert-type' => 'error',
                );

                return back()->with($notification);
            }

            // Update The new Password
            User::whereId(auth::user()->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            $notification = array(
                'message' => 'User Password Changed Successfully',
                'alert-type' => 'success',
            );

            return back()->with($notification);

    } // End Method




}

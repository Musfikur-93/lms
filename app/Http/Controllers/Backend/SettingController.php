<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingController extends Controller
{
    public function SmtpSetting(){

        $smtp = SmtpSetting::find(1);
        return view('admin.backend.setting.smtp_update',compact('smtp'));

    } // End Method


    public function SmtpUpdate(Request $request){

        $smtp_id = $request->id;

        SmtpSetting::find($smtp_id)->update([
            'mailer' => $request->mailer,
            'host' => $request->host,
            'port' => $request->port,
            'username' => $request->username,
            'password' => $request->password,
            'encryption' => $request->encryption,
            'from_address' => $request->from_address
        ]);

        $notification = array(
            'message' => 'Smtp Setting Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method


    ///////////// Admin Website Setting Method ///////////////

    public function SiteSetting(){

        $site = SiteSetting::find(1);
        return view('admin.backend.setting.site_update',compact('site'));

    } // End Method


    public function SiteUpdate(Request $request){

        $site_id = $request->id;
        $oldimg = $request->old_img;

        if ($request->file('logo')) {
            $image = $request->file('logo');

            $manager = new ImageManager(new Driver());
            $namge_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(140,41);
            $img->toJpeg(80)->save(base_path('public/upload/logo/'.$namge_gen));
            $save_url = 'upload/logo/'.$namge_gen;

            if (file_exists($oldimg)) {
                unlink($oldimg);
            }

            SiteSetting::find($site_id)->update([
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'copyright' => $request->copyright,
                'logo' => $save_url,
            ]);


            $notification = array(
                'message' => 'Site Setting Updated Successfully',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);

        }else{

            SiteSetting::find($site_id)->update([
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'copyright' => $request->copyright,
            ]);


            $notification = array(
                'message' => 'Site Setting Updated Successfully',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);
        }

    } // End Method


}

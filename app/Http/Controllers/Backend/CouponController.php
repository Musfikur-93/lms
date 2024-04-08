<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\SubCategory;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function AdminAllCoupon(){

        $coupon = Coupon::latest()->get();
        return view('admin.backend.coupon.all_coupon',compact('coupon'));

    } // End Method


    public function AdminAddCoupon(){

        return view('admin.backend.coupon.add_coupon');

    } // End Method


    public function AdminStoreCoupon(Request $request){

        Coupon::insert([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Coupon Added Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('admin.all.coupon')->with($notification);

    } // End Method





}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function AllCategory(){

        $category = Category::latest()->get();
        return view('admin.backend.category.all_category',compact('category'));

    } // End Method


    public function AddCategory(){

        return view('admin.backend.category.add_category');

    } // End Method


    public function StoreCategory(Request $request){

        if ($request->file('image')) {
            $image = $request->file('image');

            $manager = new ImageManager(new Driver());
            $namge_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(370,246);
            $img->toJpeg(80)->save(base_path('public/upload/category/'.$namge_gen));
            $save_url = 'upload/category/'.$namge_gen;

            Category::insert([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Category Inserted Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('all.category')->with($notification);

        }



    } // End Method


    public function EditCategory($id){

        $category = Category::find($id);
        return view('admin.backend.category.edit_category',compact('category'));

    } // End Method


    public function UpdateCategory(Request $request){

        $cat_id = $request->id;
        $oldimg = $request->old_img;

        if ($request->file('image')) {
            $image = $request->file('image');

            $manager = new ImageManager(new Driver());
            $namge_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(370,246);
            $img->toJpeg(80)->save(base_path('public/upload/category/'.$namge_gen));
            $save_url = 'upload/category/'.$namge_gen;

            if (file_exists($oldimg)) {
                unlink($oldimg);
            }

            Category::find($cat_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'image' => $save_url,
            ]);


            $notification = array(
                'message' => 'Category Updated With Image',
                'alert-type' => 'success',
            );

            return redirect()->route('all.category')->with($notification);

        }else{

            Category::find($cat_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
            ]);


            $notification = array(
                'message' => 'Category Updated Without Image',
                'alert-type' => 'success',
            );

            return redirect()->route('all.category')->with($notification);
        }

    } // End Method



}

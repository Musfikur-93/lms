<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function AllBlogCategory(){

        $category = BlogCategory::latest()->get();
        return view('admin.backend.blogcategory.blog_category',compact('category'));

    } // End Method


    public function BlogCategoryStore(Request $request){

        BlogCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'BlogCategory Inserted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method


    public function EditBlogCategory($id){

        $categories = BlogCategory::find($id);
        return response()->json($categories);

    } // End Method


    public function BlogCategoryUpdate(Request $request){

        $cat_id = $request->cat_id;

        BlogCategory::find($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'BlogCategory Updated Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method


    public function DeleteBlogCategory($id){

        BlogCategory::find($id)->delete();

        $notification = array(
            'message' => 'BlogCategory Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method



    ///////////////// Blog Post Method ////////////

    public function BlogPost(){

        $post = BlogPost::latest()->get();
        return view('admin.backend.post.all_post',compact('post'));

    } // End Method


    public function AddBlogPost(Request $request){

        $blogcat = BlogCategory::latest()->get();
        return view('admin.backend.post.add_post',compact('blogcat'));

    } // End Method


    public function StoreBlogPost(Request $request){

        if ($request->file('post_image')) {
            $image = $request->file('post_image');

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(370,247);
            $img->toJpeg(80)->save(base_path('public/upload/post/'.$name_gen));
            $save_url = 'upload/post/'.$name_gen;

            BlogPost::insert([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_descp' => $request->long_descp,
                'post_tags' => $request->post_tags,
                'post_image' => $save_url,
                'created_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'Blog Post Inserted Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('blog.post')->with($notification);

        }

    } // End Method


    public function EditBlogPost($id){

        $blogcat = BlogCategory::latest()->get();
        $post = BlogPost::find($id);
        return view('admin.backend.post.edit_post',compact('post','blogcat'));

    } // End Method



    public function UpdateBlogPost(Request $request){

        $post_id = $request->id;
        $oldimg = $request->old_img;

        if ($request->file('post_image')) {
            $image = $request->file('post_image');

            $manager = new ImageManager(new Driver());
            $namge_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img = $img->resize(370,247);
            $img->toJpeg(80)->save(base_path('public/upload/post/'.$namge_gen));
            $save_url = 'upload/post/'.$namge_gen;

            if (file_exists($oldimg)) {
                unlink($oldimg);
            }

            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_descp' => $request->long_descp,
                'post_tags' => $request->post_tags,
                'post_image' => $save_url,
                'created_at' => Carbon::now()
            ]);


            $notification = array(
                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('blog.post')->with($notification);

        }else{

            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'long_descp' => $request->long_descp,
                'post_tags' => $request->post_tags,
                'created_at' => Carbon::now()
            ]);


            $notification = array(
                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success',
            );

            return redirect()->route('blog.post')->with($notification);
        }

    } // End Method


    public function DeleteBlogPost($id){

        $item = BlogPost::find($id);
        $img = $item->post_image;
        unlink($img);

        BlogPost::find($id)->delete();

        $notification = array(
            'message' => 'Blog Post Deleted Successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);

    } // End Method



    ////////////// Frontend Blog Details Method ///////////////

    public function BlogDetails($slug){

        $blog = BlogPost::where('post_slug',$slug)->first();
        $tags = $blog->post_tags;
        $tags_all = explode(',',$tags);
        return view('frontend.blog.blog_details',compact('blog','tags_all'));

    } // End Method




}

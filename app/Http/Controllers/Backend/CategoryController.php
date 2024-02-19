<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function AllCategory(){

        $category = Category::all();
        return view('admin.category.all_category',compact('category'));

    } // End Method



}

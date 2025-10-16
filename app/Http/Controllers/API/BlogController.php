<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Helpers\CurrentUser;
use Validator;
use App\Models\Blog;

class BlogController extends Controller
{
    
    //get all blog category ..........
    public function getAllBlogCategory(){

        //To get all the credit card data...
        $blogCategoryData = BlogCategory::orderBy('id','DESC')->get();

        if(!empty($blogCategoryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'blogCategoryData'   =>  $blogCategoryData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }

    }
    
    //get all blog data ..........
    public function getAllBlogData(){

        //To get all the credit card data...
        $blogData = Blog::orderBy('id','DESC')->where('type','frontend')
                    ->with(['blogCategoryData'])->get();

        if(!empty($blogData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'blogData'   =>  $blogData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }

    }
    
    //get all blog details data ..........
    public function getBlogDetailsData($id){

        //To get all the credit card data...
        $singleBlogData = Blog::where('id',$id)->where('type','frontend')
                    ->with(['blogCategoryData'])->first();

        if(!empty($singleBlogData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleBlogData'   =>  $singleBlogData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }

    }


    //get category wise blog...........
    public function getCategoryWiseBlog($id){

        //To get all the credit card data...
        $blogData = Blog::orderBy('id','DESC')->where('blog_category_id',$id)->where('type','frontend')
                    ->with(['blogCategoryData'])->get();

        if(!empty($blogData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'blogData'   =>  $blogData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }

    }


}

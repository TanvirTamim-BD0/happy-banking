<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;
use App\Helpers\CurrentUser;
use App\Models\Blog;
use App\Models\BlogCategory;
use Image;

class BlogController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:blog-list|blog-create|blog-edit|blog-delete', ['only' => ['index','show']]);
         $this->middleware('permission:blog-create', ['only' => ['create','store']]);
         $this->middleware('permission:blog-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:blog-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();

        //To fetch all the blog data with user id...
        $blogData = Blog::where('user_id', $userId)->get();

        return view('backend.blog.index',compact('blogData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();

        //To fetch all the blog category data...
        $blogCategoryData = BlogCategory::where('user_id', $userId)->get();

        return view('backend.blog.create', compact('blogCategoryData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=> 'required',
            'description'=> 'required',
            'image'=> 'nullable|image|mimes:jpg,jpeg,png,gif,svg',
        ]);
 

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        // For blog description title...
        $blogRemovalDescription = strip_tags($request->description);
        $originalBlogDescription = preg_replace("/\s|&nbsp;/"," ",$blogRemovalDescription);
        
        $data['solid_description'] = $originalBlogDescription;

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/blog/');
            Image::make($file)->resize(1920, 1080)->save($destinationPath.$imageName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/blog/thumbnail/');
            Image::make($file)->resize(211,30)->save($destinationPath.$imageName);
            
            $data['image'] = $imageName;

        }

        if(Blog::create($data)){
            Toastr::success('Blog created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('blog.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();

        //To fetch all the blog category data...
        $blogCategoryData = BlogCategory::where('user_id', $userId)->get();

        //To fetch single blog data...
        $singleBlogData = Blog::find($id);

        return view('backend.blog.edit' , compact('singleBlogData','blogCategoryData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=> 'required',
            'description'=> 'required',
            'image'=> 'nullable|image|mimes:jpg,jpeg,png,gif,svg',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;
        
        // For blog description title...
        $blogRemovalDescription = strip_tags($request->description);
        $originalBlogDescription = preg_replace("/\s|&nbsp;/"," ",$blogRemovalDescription);
        
        $data['solid_description'] = $originalBlogDescription;

        //To fetch single blog data...
        $singleBlogData = Blog::findOrFail($id);
        
        if($request->hasFile('image')) {
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/blog/');
            if(file_exists($destinationPath.$singleBlogData->image)){
                if($singleBlogData->image != ''){
                    unlink($destinationPath.$singleBlogData->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/blog/thumbnail/');
                    unlink($destinationPath.$singleBlogData->image);
                }
            }
            
            $file = $request->file('image');
            $imageName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/blog/');
            Image::make($file)->resize(1920, 1080)->save($destinationPath.$imageName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/blog/thumbnail/');
            Image::make($file)->resize(211,30)->save($destinationPath.$imageName);
            
            $data['image'] = $imageName;

        }

        if($singleBlogData->update($data)){
            Toastr::success('Blog updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('blog.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $singleBlogData = Blog::findOrFail($id);

        if($singleBlogData->delete()){
            Toastr::success('Blog Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}

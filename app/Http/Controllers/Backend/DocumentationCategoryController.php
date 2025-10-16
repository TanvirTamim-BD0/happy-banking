<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DocumentationCategory;
use Illuminate\Http\Request;
use App\Helpers\CurrentUser;
use App\Models\BlogCategory;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;

class DocumentationCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:category-of-documentation-list|category-of-documentation-create|category-of-documentation-edit|category-of-documentation-delete', ['only' => ['index','show']]);
        $this->middleware('permission:category-of-documentation-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-of-documentation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-of-documentation-delete', ['only' => ['destroy']]);
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
        
        //To fetch all the documentation category data with user id...
        $documentationCategoryData = DocumentationCategory::orderBy('id', 'DESC')->where('user_id', $userId)->get();

        return view('backend.documentationCategory.index',compact('documentationCategoryData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'documentation_category_name'=> 'required',
            'image'=> 'required',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To check bank image...
        if($request->image){
            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/documentationCategoryImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/documentationCategoryImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if(DocumentationCategory::create($data)){
            Toastr::success('Documentation Category created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('documentation-category.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentationCategory  $documentationCategory
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentationCategory $documentationCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentationCategory  $documentationCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentationCategory $documentationCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentationCategory  $documentationCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentationCategory $documentationCategory)
    {
        
        $request->validate([
            'documentation_category_name'=> 'required',
            'image'=> 'nullable',
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To fetch single documentation category data...
        $singleDocumentationCategoryData = DocumentationCategory::findOrFail($documentationCategory->id);

        //To check bank image...
        if($request->image){
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/documentationCategoryImage/');
            if(file_exists($destinationPath.$singleDocumentationCategoryData->image)){
                if($singleDocumentationCategoryData->image != ''){
                    unlink($destinationPath.$singleDocumentationCategoryData->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/documentationCategoryImage/thumbnail/');
                    unlink($destinationPath.$singleDocumentationCategoryData->image);
                }
            }

            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/documentationCategoryImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/documentationCategoryImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if($singleDocumentationCategoryData->update($data)){
            Toastr::success('Documentation Category updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('documentation-category.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentationCategory  $documentationCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $singleDocumentationCategoryData = DocumentationCategory::findOrFail($id);

        //To remove previous file...
        $destinationPath = public_path('backend/uploads/documentationCategoryImage/');
        if(file_exists($destinationPath.$singleDocumentationCategoryData->image)){
            if($singleDocumentationCategoryData->image != ''){
                unlink($destinationPath.$singleDocumentationCategoryData->image);

                //For thumbnail...
                $destinationPath = public_path('backend/uploads/documentationCategoryImage/thumbnail/');
                unlink($destinationPath.$singleDocumentationCategoryData->image);
            }
        }

        if($singleDocumentationCategoryData->delete()){
            Toastr::success('Documentation Category Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentationCategory;
use App\Models\Documentation;
use App\Helpers\CurrentUser;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;

class DocumentationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:documentation-list|documentation-create|documentation-edit|documentation-delete', ['only' => ['index','show']]);
        $this->middleware('permission:documentation-create', ['only' => ['create','store']]);
        $this->middleware('permission:documentation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:documentation-delete', ['only' => ['destroy']]);
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

         //To fetch all the Documentation data with user id...
         $documentationData = Documentation::orderBy('id', 'DESC')->where('user_id', $userId)->get();
 
         return view('backend.documentation.index',compact('documentationData'));
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

        //To fetch all the Documentation category data...
        $documentationCategoryData = DocumentationCategory::where('user_id', $userId)->get();

        return view('backend.documentation.create', compact('documentationCategoryData'));

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
            'documentation_category_id'=> 'required',
            'description'=> 'required',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff,tif|max:2048'
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //For Documentation description...
        $documentationRemovalDescription = strip_tags($request->description);
        $originalDocumentationDescription = preg_replace("/\s|&nbsp;/"," ",$documentationRemovalDescription);
        $data['solid_description'] = $originalDocumentationDescription;

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/documentationImage/');
            Image::make($file)->resize(1920, 1080)->save($destinationPath.$imageName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/documentationImage/thumbnail/');
            Image::make($file)->resize(211,30)->save($destinationPath.$imageName);
            
            $data['image'] = $imageName;

        }

        if(Documentation::create($data)){
            Toastr::success('Documentation created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('documentation.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Documentation  $documentation
     * @return \Illuminate\Http\Response
     */
    public function show(Documentation $documentation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Documentation  $documentation
     * @return \Illuminate\Http\Response
     */
    public function edit(Documentation $documentation)
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();

        //To fetch all the Documentation category data...
        $documentationCategoryData = DocumentationCategory::where('user_id', $userId)->get();

        //To fetch single Documentation data...
        $singleDocumentationData = Documentation::find($documentation->id);

        return view('backend.documentation.edit' , compact('singleDocumentationData','documentationCategoryData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Documentation  $documentation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=> 'required',
            'description'=> 'nullable',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp,tiff,tif|max:2048'
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;
        
        // For documentation description title...
        $documentationRemovalDescription = strip_tags($request->description);
        $originalDocumentationDescription = preg_replace("/\s|&nbsp;/"," ",$documentationRemovalDescription);
        
        $data['solid_description'] = $originalDocumentationDescription;

        //To fetch single documentation data...
        $singleDocumentationData = Documentation::findOrFail($id);
        
        if($request->hasFile('image')) {
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/documentationImage/');
            if(file_exists($destinationPath.$singleDocumentationData->image)){
                if($singleDocumentationData->image != ''){
                    unlink($destinationPath.$singleDocumentationData->image);

                    //For thumbnail...
                    $destinationPath = public_path('backend/uploads/documentationImage/thumbnail/');
                    unlink($destinationPath.$singleDocumentationData->image);
                }
            }
            
            $file = $request->file('image');
            $imageName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/documentationImage/');
            Image::make($file)->resize(1920, 1080)->save($destinationPath.$imageName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/documentationImage/thumbnail/');
            Image::make($file)->resize(211,30)->save($destinationPath.$imageName);
            
            $data['image'] = $imageName;

        }

        if($singleDocumentationData->update($data)){
            Toastr::success('Documentation updateed successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('documentation.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Documentation  $documentation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $singleDocumentationData = Documentation::findOrFail($id);

        //To remove previous file...
        $destinationPath = public_path('backend/uploads/documentationImage/');
        if(file_exists($destinationPath.$singleDocumentationData->image)){
            if($singleDocumentationData->image != ''){
                unlink($destinationPath.$singleDocumentationData->image);

                //For thumbnail...
                $destinationPath = public_path('backend/uploads/documentationImage/thumbnail/');
                unlink($destinationPath.$singleDocumentationData->image);
            }
        }

        if($singleDocumentationData->delete()){
            Toastr::success('Documentation Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}

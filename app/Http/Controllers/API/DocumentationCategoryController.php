<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentationCategory;
use App\Helpers\CurrentUser;
use Image;

class DocumentationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //To get all the documentation category...
        $documentationCategoryData = DocumentationCategory::orderBy('id','DESC')->get();

        if(!empty($documentationCategoryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'documentationCategoryData'   =>  $documentationCategoryData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documentation_category_name'=> 'required',
            'image'=> 'required|mimes:jpg,jpeg,png,gif,svg',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

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

        if($result = DocumentationCategory::create($data)){
            return response()->json([
                'message'   =>  'DocumentationCategory created successfully.',
                'documentationCategoryData'   =>  $result,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
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
        $singleDocumentationCategoryData = DocumentationCategory::find($id);

        if(!empty($singleDocumentationCategoryData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleDocumentationCategoryData'   =>  $singleDocumentationCategoryData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
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
        $validator = Validator::make($request->all(), [
            'documentation_category_name'=> 'required',
            'image'=> 'nullable|mimes:jpg,jpeg,png,gif,svg',
        ]);

        if ($validator->fails()) {
        	return response(['errors'=>$validator->errors()->all()], 422);
        }

        //To fet userId..
        $userId = CurrentUser::getUserId();
        $data = $request->all();
        $data['user_id'] = $userId;

        //To fetch single blog category data...
        $singleDocumentationCategoryData = DocumentationCategory::findOrFail($id);

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
            return response()->json([
                'message'   =>  'DocumentationCategory updated successfully.',
                'singleDocumentationCategoryData'   =>  $singleDocumentationCategoryData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.!',
                'status_code'   => 500
            ], 500);
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
            return response()->json([
                'message'   =>  'DocumentationCategory Deleted Successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.!',
                'status_code'   => 500
            ], 500);
        }
    }
}

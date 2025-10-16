<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documentation;
use App\Models\FrontendNote;
use App\Models\DocumentationCategory;
use App\Helpers\FrontendNoteType;
use App\Helpers\CurrentUser;
use Image;

class DocumentationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //To get all the documentation...
        $documentationData = Documentation::orderBy('id','DESC')->get();

        if(!empty($documentationData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'documentationData'   =>  $documentationData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }

    //To get documentation category...
    public function getDocumentationCategory()
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
   
    //To get documentation  with category wise...
    public function getDocumentationWisDocsCate($id)
    {
        //To get all the documentation...
        $documentationData = Documentation::orderBy('id','DESC')->where('documentation_category_id', $id)
                            ->with(['documentationCategoryData'])->get();

        if(!empty($documentationData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'documentationData'   =>  $documentationData,
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
            'documentation_category_id'=> 'required',
            'type'=> 'required',
            'title'=> 'required',
            'description'=> 'required',
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
            $destinationPath = public_path('backend/uploads/documentationImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/documentationImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if($result = Documentation::create($data)){
            return response()->json([
                'message'   =>  'Documentation created successfully.',
                'documentationData'   =>  $result,
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
        $singleDocumentationData = Documentation::find($id);

        if(!empty($singleDocumentationData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'singleDocumentationData'   =>  $singleDocumentationData,
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
        $singleDocumentationData = Documentation::findOrFail($id);

        //To check bank image...
        if($request->image){
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
            $fileName = time().'.'.$file->getClientOriginalExtension();

            //For large size image...
            $destinationPath = public_path('backend/uploads/documentationImage/');
            Image::make($file)->save($destinationPath.$fileName);
            
            //For thumbnail size image...
            $destinationPath = public_path('backend/uploads/documentationImage/thumbnail/');
            Image::make($file)->resize(500,400)->save($destinationPath.$fileName);
            
            $data['image'] = $fileName;
        }

        if($singleDocumentationData->update($data)){
            return response()->json([
                'message'   =>  'Documentation updated successfully.',
                'singleDocumentationData'   =>  $singleDocumentationData,
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
            return response()->json([
                'message'   =>  'Documentation Deleted Successfully.',
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Something is wrong.!',
                'status_code'   => 500
            ], 500);
        }
    }

    //To get all the frontend note type data...
    public function getFrontendNoteType()
    {
        //To get frontend note type data...
        $noteTypeData = FrontendNoteType::getNoteTypeData();

        if(!empty($noteTypeData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'noteTypeData'   =>  $noteTypeData,
                'status_code'   => 201
            ], 201);
        }else{
            return response()->json([
                'message'   =>  'Sorry you have no data.',
                'status_code'   => 500
            ], 500);
        }
    }
    
    //To get all the frontend note data...
    public function getFrontendNoteData(Request $request)
    {
        //To get frontend note type data...
        $frontendNoteData = FrontendNote::where('description_type', $request->note_type)->first();

        if(!empty($frontendNoteData)){
            return response()->json([
                'message'   =>  'Successfully loaded data.',
                'frontendNoteData'   =>  $frontendNoteData,
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

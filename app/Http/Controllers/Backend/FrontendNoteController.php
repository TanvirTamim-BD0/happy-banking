<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FrontendNote;
use Illuminate\Http\Request;
use App\Helpers\FrontendNoteType;
use App\Helpers\CurrentUser;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;

class FrontendNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //To fet userId..
        $userId = CurrentUser::getUserId();
        $frontendNotedata = FrontendNote::where('user_id', $userId)->get();

        return view('backend.frontendNote.index', compact('frontendNotedata'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //To fetch all the Frontend Note data...
        $frontendNoteArrayData = FrontendNoteType::getNoteTypeData();

        //To fet userId & get description_type with groupBy...
        $userId = CurrentUser::getUserId();
        $frontendNotedata = FrontendNote::where('user_id', $userId)->select('description_type')->groupBy('description_type')
                            ->pluck('description_type')->toArray();

        $getArrayData = [];
        foreach($frontendNoteArrayData as $key=>$item){
            if (!in_array($item['name'], $frontendNotedata)) {
                $getArrayData[] = $item['name'];
            }
        }

        return view('backend.frontendNote.create', compact('getArrayData'));
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
            'description'=> 'required',
            'description_type'=> 'required'
        ]);

        //To fetch userId..
        $userId = CurrentUser::getUserId();

        $data = $request->all();
        $data['user_id'] = $userId;
        // For blog description title...
        $frontendNoteRemovalDescription = strip_tags($request->description);
        $originalBlogDescription = preg_replace("/\s|&nbsp;/"," ",$frontendNoteRemovalDescription);
        $data['solid_description'] = $originalBlogDescription;

        if(FrontendNote::create($data)){
            Toastr::success('FrontendNote created successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('frontend-note.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FrontendNote  $frontendNote
     * @return \Illuminate\Http\Response
     */
    public function show(FrontendNote $frontendNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FrontendNote  $frontendNote
     * @return \Illuminate\Http\Response
     */
    public function edit(FrontendNote $frontendNote)
    {
        //To fetch all the Frontend Note data...
        $frontendNoteArrayData = FrontendNoteType::getNoteTypeData();

        //To fet userId & get description_type with groupBy...
        $userId = CurrentUser::getUserId();
        $frontendNotedata = FrontendNote::where('user_id', $userId)->select('description_type')->groupBy('description_type')
                            ->pluck('description_type')->toArray();

        $getArrayData = [];
        foreach($frontendNoteArrayData as $key=>$item){
            if($item['name'] == $frontendNote->description_type){
                $getArrayData[] = $item['name'];
            }else{
                if (!in_array($item['name'], $frontendNotedata)) {
                    $getArrayData[] = $item['name'];
                }
            }
        }

        return view('backend.frontendNote.edit' , compact('frontendNote','getArrayData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FrontendNote  $frontendNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FrontendNote $frontendNote)
    {
        $request->validate([
            'description'=> 'required',
            'description_type'=> 'required'
        ]);

        //To fet userId..
        $userId = CurrentUser::getUserId();

        $data = $request->all();
        $data['user_id'] = $userId;
        // For blog description title...
        $frontendNoteRemovalDescription = strip_tags($request->description);
        $originalBlogDescription = preg_replace("/\s|&nbsp;/"," ",$frontendNoteRemovalDescription);
        $data['solid_description'] = $originalBlogDescription;

        if($frontendNote->update($data)){
            Toastr::success('FrontendNote updated successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('frontend-note.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FrontendNote  $frontendNote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $singleFrontendNoteData = FrontendNote::findOrFail($id);

        if($singleFrontendNoteData->delete()){
            Toastr::success('FrontendNote deleted successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back();
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers\backend\userRole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserActivity;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use DB;
use Hash;
use Auth;
use Spatie\Permission\Models\Permission;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'superadmin'){
            $userData = User::orderBy('id','DESC')->get();
        }elseif(Auth::user()->role == 'admin'){
            $userData = User::orderBy('id','DESC')->where('admin_id', Auth::user()->id)->get();
        }elseif(Auth::user()->role == 'manager'){
            $userData = User::orderBy('id','DESC')->where('admin_id', Auth::user()->admin_id)
                    ->where('role','user')->get();
        }else{
            $userData[] = null;
        }

        return view('backend.userRole.users.index',compact('userData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role == 'superadmin'){
            $roles = Role::whereNotIn('name', ['superadmin'])->get();
        }elseif(Auth::user()->role == 'admin'){
            $roles = Role::whereNotIn('name', ['superadmin','admin'])->get();
        }elseif(Auth::user()->role == 'manager'){
            $roles = Role::whereNotIn('name', ['superadmin','admin','manager'])->get();
        }else{
            $roles[] = null;
        }

        $userData[] = null;

        return view('backend.userRole.users.create',compact('roles','userData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'password' => 'required|same:password_confirmation',
            'roles' => 'required'
        ]);
    
        $data = $request->all();
        $userRole = Role::where('id', $request->roles)->first();

        //To set user role..
        if($userRole->name == 'admin'){
            $data['role'] = 'admin';
        }
        if($userRole->name == "manager"){
            $data['role'] = 'manager';
        }
        if($userRole->name == "user"){
            $data['role'] = 'user';
        }

        //To check user role..
        if(Auth::user()->role == 'admin'){
            $data['admin_id'] = Auth::user()->id;
            $data['manager_id'] = Auth::user()->id;
        }
        if(Auth::user()->role == 'manager'){
            $data['admin_id'] = Auth::user()->admin_id;
            $data['manager_id'] = Auth::user()->id;
        }

        if($request->image){
            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('backend/uploads/userRole/');
            $file->move($destinationPath,$fileName);
            $data['image'] = $fileName;
        }


        $data['password'] = Hash::make($data['password']);
    
        if($user = User::create($data)){
            $user->assignRole($request->input('roles'));
            Toastr::success('User Created Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('users.index');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
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
        if(Auth::user()->role == 'superadmin'){
            $roles = Role::whereNotIn('name', ['superadmin'])->get();
        }elseif(Auth::user()->role == 'admin'){
            $roles = Role::whereNotIn('name', ['superadmin','admin'])->get();
        }elseif(Auth::user()->role == 'manager'){
            $roles = Role::whereNotIn('name', ['superadmin','admin','manager'])->get();
        }else{
            $roles[] = null;
        }

        $userData = User::find($id);
    
        return view('backend.userRole.users.edit',compact('userData','roles'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'mobile' => 'required|unique:users,mobile,'.$id,
            'password' => 'nullable|min:3|same:password_confirmation'
        ]);
    
        $data = $request->all();
        $userData = User::find($id);
        $userRole = Role::where('id', $request->roles)->first();

        if($userRole != null){
            //To set user role..
            if($userRole->name == 'admin'){
                $data['role'] = 'admin';
            }
            if($userRole->name == "manager"){
                $data['role'] = 'manager';
            }
            if($userRole->name == "staff"){
                $data['role'] = 'staff';
            }
        }

        //To check user role..
        if(Auth::user()->role == 'admin'){
            $data['admin_id'] = Auth::user()->id;
            $data['manager_id'] = Auth::user()->id;
        }
        if(Auth::user()->role == 'manager'){
            $data['admin_id'] = Auth::user()->admin_id;
            $data['manager_id'] = Auth::user()->id;
        }

        //To check password is empty or not...
        if($request->password != null){
            $data['password'] = Hash::make($data['password']);
        }else{
            $data['password'] = $userData->password;
        }

        if($request->image){
            //To remove previous file...
            $destinationPath = public_path('backend/uploads/userRole/');
            if(file_exists($destinationPath.$userData->image)){
                if($userData->image != ''){
                    unlink($destinationPath.$userData->image);
                }
            }

            $file = $request->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('backend/uploads/userRole/');
            $file->move($destinationPath,$fileName);
            $data['image'] = $fileName;
        }

        if($userData->update($data)){
            if($userData->role != 'superadmin'){
                DB::table('model_has_roles')->where('model_id',$id)->delete();
                $userData->assignRole($request->input('roles'));
            }
            
            Toastr::success('User Updated Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->route('users.index')->with('message','Successfully User Updated');
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
        $user = User::findOrFail($id);
        if($user->delete()){
            Toastr::success('User Deleted Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back()->with('message','Successfully User Deleted');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back()->with('message','Something is wrong.!');;
        }
    }



    //To active user...
    public function userActive($id)
    {
        $user = User::find($id);
        $user->status = 1;

        if($user->save()){
            Toastr::success('User Activated Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back()->with('message','Successfully User Deleted');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back()->with('message','Something is wrong.!');;
        }
    }
    
    //To active user...
    public function userInactive($id)
    {
        $user = User::find($id);
        $user->status = 0;

        if($user->save()){
            Toastr::success('User In-Activated Successfully.', 'Success', ["progressbar" => true]);
            return redirect()->back()->with('message','Successfully User Deleted');
        }else{
            Toastr::error('Soething is wrong!.', 'Error', ["progressbar" => true]);
            return redirect()->back()->with('message','Something is wrong.!');;
        }
    }

    //To get user profile security page...
    public function userSecurity($id)
    {
        //To get user data..
        $singleUser = User::where('id', $id)->first();

    	return view('backend.userRole.users.profile.security', compact('singleUser'));
    }


    public function userPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($request->new_password == $request->confirm_password) {

            User::find($id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->route('users.index')->with('message','Successfully Password Updated');

        }else{
            return redirect()->back()->with('error','Something is wrong.!');
        }
    }

    //To get users profile page...
    public function userProfile($id)
    {
        //To get user data..
        $singleUser = User::where('id', $id)->first();
        //To get user activity...
        $singleUserActivity = UserActivity::orderBy('id','desc')->where('user_id', $singleUser->id)->get();

    	return view('backend.userRole.users.profile.index', compact('singleUser','singleUserActivity'));
    }

    //To get user activity with from & to date...
    public function userActivityFilter(Request $request, $id)
    {
        $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        //To get user data..
        $singleUser = User::where('id', $id)->first();
        $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
        //To get user activity...
        $singleUserActivity = UserActivity::orderBy('id','desc')->where('user_id', $singleUser->id)
                                ->where('date', '>=', $fromDate )->where('date', '<=', $toDate)->get();

    	return view('backend.userRole.users.profile.index', compact('singleUser','singleUserActivity'));
    }

}

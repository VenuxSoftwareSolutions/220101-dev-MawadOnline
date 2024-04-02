<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Mail\SellerStaffMail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreStaffRequest;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_staffs'])->only('index');
        $this->middleware(['permission:add_staff'])->only('create');
        $this->middleware(['permission:edit_staff'])->only('edit');
        $this->middleware(['permission:delete_staff'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller= Role::where('name','seller')->first();
        $users=User::where('user_type','staff')->get();
        $staffs = Staff::whereIn('user_id',$users->pluck('id'))->orderBy('id','desc')->paginate(10);
        return view('backend.staff.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id','!=',1)->where('role_type',0)->where('guard_name','web')->orderBy('id', 'desc')->get();
        return view('backend.staff.staffs.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffRequest $request)
    {
        abort_if(!auth('web')->user()->can('add_staff'), Response::HTTP_FORBIDDEN, 'ACCESS FORBIDDEN');

        $url = url('/admin');
        $admin=Auth::user();
        if(User::where('email', $request->email)->first() == null){
            $user = new User;
            $user->name = $request->first_name.' '.$request->last_name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->mobile;
            $user->user_type = "staff";
            $user->status = "Enabled";
            $password=$this->generatePassword(12);
            $user->password = Hash::make($password);
            if($user->save()){
                $staff = new Staff;
                $staff->user_id = $user->id;
                $staff->created_by = $admin->id;
                $staff->role_id = $request->role_id;
                $role=Role::findOrFail($request->role_id);
                $user->assignRole($role->name);
                if($staff->save()){
                    Mail::to($user->email)->send(new SellerStaffMail($user, $role, $password, $admin , $url));
                    flash(translate('Staff has been inserted successfully'))->success();
                    return redirect()->route('staffs.index');
                }
            }
        }

        flash(translate('Email already used'))->error();
        return back();
    }

    public function generatePassword($length = 12) {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()-_+=\/{}[]|';

        $password = '';

        // Choose at least one character from each character set
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

        // Fill the rest of the password with random characters
        $remainingLength = $length - 4; // Subtracting 4 as we already added one from each character set
        $allChars = $uppercase . $lowercase . $numbers . $specialChars;
        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        // Shuffle the password to randomize the positions of the characters
        $password = str_shuffle($password);

        return $password;
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
        $staff = Staff::findOrFail(decrypt($id));
        $roles = $roles = Role::where('id','!=',1)->orderBy('id', 'desc')->get();
        return view('backend.staff.staffs.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreStaffRequest $request, $id)
    {
        abort_if(!auth('web')->user()->can('edit_staff'), Response::HTTP_FORBIDDEN, 'ACCESS FORBIDDEN');

        $staff = Staff::findOrFail($id);
        $user = $staff->user;
        $user->name = $request->name;
        //$user->email = $request->email;
        $user->phone = $request->mobile;
        // if(strlen($request->password) > 0){
        //     $user->password = Hash::make($request->password);
        // }
        if($user->save()){
            $staff->role_id = $request->role_id;
            if($staff->save()){
                $user->syncRoles(Role::findOrFail($request->role_id)->name);
                flash(translate('Staff has been updated successfully'))->success();
                return redirect()->route('staffs.index');
            }
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy(Staff::findOrFail($id)->user->id);
        if(Staff::destroy($id)){
            flash(translate('Staff has been deleted successfully'))->success();
            return redirect()->route('staffs.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }
}

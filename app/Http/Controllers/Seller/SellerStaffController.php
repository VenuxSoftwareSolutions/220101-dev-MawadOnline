<?php

namespace App\Http\Controllers\Seller;

use Auth;
use Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Mail\SellerStaffMail;
use Illuminate\Support\Facades\Mail;

class SellerStaffController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:seller_view_all_staffs'])->only('index');
        $this->middleware(['permission:seller_add_staff'])->only('create');
        $this->middleware(['permission:seller_edit_staff'])->only('edit');
        $this->middleware(['permission:seller_delete_staff'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffs = Staff::where('seller_id',Auth::user()->id)->paginate(10);
        return view('seller.staff.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereIn('seller_id',[1,Auth::user()->id])->orderBy('id', 'desc')->get();
        return view('seller.staff.staffs.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = url('/seller/login');
        $vendor=Auth::user();
        if(User::where('email', $request->email)->first() == null){
            $user = new User;
            $user->name = $request->first_name.' '.$request->last_name;
            $user->email = $request->email;
            $user->phone = $request->mobile;
            $user->user_type = "seller";
            $password=$this->generatePassword(12);
            $user->password = Hash::make($password);
            if($user->save()){
                $staff = new Staff;
                $staff->user_id = $user->id;
                $staff->seller_id = $vendor->id;
                $staff->role_id = $request->role_id;
                $role=Role::findOrFail($request->role_id);
                $user->assignRole($role->name);
                if($staff->save()){
                    Mail::to($user->email)->send(new SellerStaffMail($user, $role, $password, $vendor , $url));
                    flash(translate('Staff has been inserted successfully'))->success();
                    return redirect()->route('seller.staffs.index');
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

        $allChars = $uppercase . $lowercase . $numbers . $specialChars;
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

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
        $roles = $roles = Role::where('seller_id','!=',null)->orderBy('id', 'desc')->get();
        return view('seller.staff.staffs.edit', compact('staff', 'roles'));
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
        $staff = Staff::findOrFail($id);
        $user = $staff->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->mobile;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){
            $staff->role_id = $request->role_id;
            if($staff->save()){
                $user->syncRoles(Role::findOrFail($request->role_id)->name);
                flash(translate('Staff has been updated successfully'))->success();
                return redirect()->route('seller.staffs.index');
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
            return redirect()->route('seller.staffs.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }
}

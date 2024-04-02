<?php

namespace App\Http\Controllers\Seller;

use Auth;
use Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Mail\SellerStaffMail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreStaffRequest;
use Illuminate\Support\Facades\Validator;

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
        $staffs = Staff::where('created_by',Auth::user()->owner_id)->orderBy('id','desc')->groupBy('user_id')->paginate(10);
        return view('seller.staff.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('role_type',1)->whereIn('created_by',[1,Auth::user()->owner_id])->where('package_id',null)->where('name','!=','seller')->get();
        return view('seller.staff.staffs.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffRequest $request)
    {
        abort_if(!auth('web')->user()->can('seller_add_staff'), Response::HTTP_FORBIDDEN, 'ACCESS FORBIDDEN');

        $url = url('/seller/login');
        $vendor=User::find(Auth::user()->owner_id);
        if(User::where('email', $request->email)->first() == null){
            try {
                $user = new User;
                $user->name = $request->first_name.' '.$request->last_name;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->phone = $request->mobile;
                $user->user_type = "seller";
                $user->step_number = 0;
                $user->status ="Draft";
                $user->owner_id = $vendor->id;
                $password = $this->generatePassword(12);
                $user->password = Hash::make($password);

                if ($user->save()) {
                    foreach ($request->role_id as $roleId) {
                    $staff = new Staff;
                    $staff->user_id = $user->id;
                    $staff->created_by = $vendor->owner_id;
                    $staff->role_id = $roleId;
                    $role = Role::findOrFail($roleId);
                    $user->assignRole($role->name);
                    $staff->save();
                }

                    if ($staff->save()) {
                        Mail::to($user->email)->send(new SellerStaffMail($user, $role, $password, $vendor , $url));
                        flash(translate('Staff has been inserted successfully'))->success();
                        DB::commit(); // Commit the transaction
                        return redirect()->route('seller.staffs.index');
                    }
                }

                DB::rollback(); // Rollback the transaction
                flash(translate('Failed to insert staff'))->error();
                return redirect()->back()->withInput();
            } catch (\Exception $e) {
                DB::rollback(); // Rollback the transaction
                flash(translate('An error occurred while inserting staff'))->error();
                return redirect()->back()->withInput();
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
        $roles = Role::where('role_type',1)->whereIn('created_by',[1,Auth::user()->owner_id])->where('package_id',null)->where('name','!=','seller')->get();
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
        abort_if(!auth('web')->user()->can('seller_edit_staff'), Response::HTTP_FORBIDDEN, 'ACCESS FORBIDDEN');

        $staff = Staff::findOrFail($id);
        $user = $staff->user;
        $user->name = $request->name;
        //$user->email = $request->email;
        $user->phone = $request->mobile;

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

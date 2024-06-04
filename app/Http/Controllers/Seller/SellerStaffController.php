<?php

namespace App\Http\Controllers\Seller;

use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Tour;
use App\Models\User;
use App\Models\Staff;
use App\Models\SellerLease;
use Illuminate\Http\Request;
use App\Mail\SellerStaffMail;
use Illuminate\Http\Response;
use App\Models\SellerLeaseDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        seller_lease_creation($user=Auth::user());

        $tour_steps=Tour::orderBy('step_number')->get();
        $staffs = Staff::where('created_by',Auth::user()->owner_id)->orderBy('id','desc')->groupBy('user_id')->paginate(10);
        return view('seller.staff.staffs.index', compact('staffs','tour_steps'));
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
        if(User::where('phone', $request->mobile)->first() != null){
            flash(__('staff.Phone already exists'))->error();
            return back();
        }
        $currentDate = Carbon::now();
        $startDay = (clone $currentDate);
        $endDay = (clone $currentDate)->subDay(1);
        $selectedRoles = $request->input('roles');
        $current_lease = SellerLease::where('vendor_id',Auth::user()->owner_id)->where('start_date', '<=', $startDay)
                                        ->where('end_date', '>=', $endDay)->first();
        $roles_id=SellerLeaseDetail::where('lease_id',$current_lease->id)->orderBy('is_used')->get();
        $cycleEnd = Carbon::parse($current_lease->end_date)->endOfDay();
        $daysToCycleEnd = $cycleEnd->diffInDays($currentDate)+1;

        // Calculate the lease cycle days
        $start_date = Carbon::parse($current_lease->start_date);
        $end_date = Carbon::parse($current_lease->end_date);
        $leaseCycleDays = $start_date->diffInDays($end_date)+1;

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
                $user->status ="Enabled";
                $user->owner_id = $vendor->id;
                $password = $this->generatePassword(12);

                $salt_generation = $this->generateSalt($request->email);
                $hashed_password =$this->hashPass($request->email ,$password , $salt_generation['salt'] ,$salt_generation['num_hashing_rounds']);

                $user->password = Hash::make($hashed_password);

                if ($user->save()) {
                    foreach ($request->role_id as $roleId) {
                        if ($roles_id->pluck('role_id')->contains($roleId)) {
                            foreach ($roles_id as $role_detail) {

                                if ($role_detail->role_id == $roleId && $role_detail->is_used == false){
                                    $role_detail->is_used = true;
                                    $role_detail->save();
                                    break;
                                }
                                elseif($role_detail->role_id == $roleId && $role_detail->is_used == true ) {
                                    $proratedLease = (10 * $daysToCycleEnd) / $leaseCycleDays;
                                    $lease_detail = new SellerLeaseDetail;
                                    $lease_detail->role_id = $roleId;
                                    $lease_detail->amount = $proratedLease;
                                    $lease_detail->lease_id = $current_lease->id;
                                    $lease_detail->is_used = true;
                                    $lease_detail->start_date = $currentDate;
                                    $lease_detail->end_date = $end_date;
                                    $lease_detail->save();
                                    $current_lease->total += $proratedLease;
                                    $current_lease->discount += $proratedLease;
                                    $current_lease->save();
                                    break;
                                }
                            }
                        }else {
                            $lease_detail = new SellerLeaseDetail;
                            $lease_detail->role_id = $roleId;
                            $lease_detail->amount = 0;
                            $lease_detail->lease_id = $current_lease->id;
                            $lease_detail->is_used = true;
                            $lease_detail->start_date = $start_date;
                            $lease_detail->end_date = $end_date;
                            $lease_detail->save();

                        }

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
                        flash(__('staff.Success! Your new team member is now part of your eShop crew. Ready to take on the world together!'))->success();
                        DB::commit(); // Commit the transaction
                        return redirect()->route('seller.staffs.index');
                    }
                }

                DB::rollback(); // Rollback the transaction
                flash(__('staff.Failed to insert staff'))->error();
                return redirect()->back()->withInput();
            } catch (\Exception $e) {
                DB::rollback(); // Rollback the transaction
                flash(__('staff.An error occurred while inserting staff'))->error();
                Log::info($e);
                return redirect()->back()->withInput();
            }
        }

        flash(translate('Email already exists'))->error();
        return back();
    }

    public function generatePassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*-_+=';

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

    function hashPass($username, $password, $salt, $rounds) {
        $hash = $username;
        for ($i=0; $i < $rounds; $i++) {
        $hash = $password . $salt . $hash;
        $hash = hash('sha3-512', $hash);
        }
        return $hash;
    }

    public function generateSalt($email)
    {

        // Your secret key for HMAC-SHA256 algorithm
        $secretKey = config('api.SALT_GENERATION_KEY'); // Make sure to set this in your .env file

        $numHashingRounds = config('api.NUMBER_HASHING_ROUNDS');

        // Generate the salt using HMAC-SHA256 algorithm
        $salt = hash_hmac('sha256', $email, $secretKey);

        // Trim the salt to 32 characters
        $salt = substr($salt, 0, 32);

        return [
            'salt' => $salt,
            'num_hashing_rounds' => $numHashingRounds
        ];
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
        try {
            abort_if(!auth('web')->user()->can('seller_edit_staff'), Response::HTTP_FORBIDDEN, 'ACCESS FORBIDDEN');

            $staff = Staff::findOrFail($id);
            $user = $staff->user;
            $staffs = Staff::where('user_id', $user->id)->get();
            $currentDate = Carbon::now();
            $startDay = (clone $currentDate);
            $endDay = (clone $currentDate)->subDay(1);
            $selectedRoles = $request->input('roles');
            $current_lease = SellerLease::where('vendor_id', Auth::user()->owner_id)
                                        ->where('start_date', '<=', $startDay)
                                        ->where('end_date', '>=', $endDay)
                                        ->first();

            if (!$current_lease) {
                throw new Exception('Current lease not found');
            }

            foreach ($staffs as $key => $st) {
                $staff_role = SellerLeaseDetail::where('lease_id', $current_lease->id)
                                            ->where('role_id', $st->role_id)
                                            ->where('is_used', true)
                                            ->where('amount', '!=', 0)
                                            ->first();
                if ($staff_role) {
                    $current_lease->total -= $staff_role->amount;
                    $current_lease->discount -= $staff_role->amount;
                    $current_lease->save();
                    $staff_role->delete();
                } else {
                    $staff_role = SellerLeaseDetail::where('lease_id', $current_lease->id)
                                                ->where('role_id', $st->role_id)
                                                ->where('is_used', true)
                                                ->first();
                    if ($staff_role) {
                        $staff_role->is_used = 0;
                        $staff_role->save();
                    }
                }
                $st->delete();
            }

            $roles_id = SellerLeaseDetail::where('lease_id', $current_lease->id)->get();
            $cycleEnd = Carbon::parse($current_lease->end_date)->endOfDay();
            $daysToCycleEnd = $cycleEnd->diffInDays($currentDate) + 1;

            $start_date = Carbon::parse($current_lease->start_date);
            $end_date = Carbon::parse($current_lease->end_date);
            $leaseCycleDays = $start_date->diffInDays($end_date) + 1;

            foreach ($request->role_id as $roleId) {
                if ($roles_id->pluck('role_id')->contains($roleId)) {
                    foreach ($roles_id as $role_detail) {
                        if ($role_detail->role_id == $roleId && $role_detail->is_used == false) {
                            $role_detail->is_used = true;
                            $role_detail->save();
                            break;
                        } elseif ($role_detail->role_id == $roleId && $role_detail->is_used == true) {
                            $proratedLease = (10 * $daysToCycleEnd) / $leaseCycleDays;
                            $lease_detail = new SellerLeaseDetail;
                            $lease_detail->role_id = $roleId;
                            $lease_detail->amount = $proratedLease;
                            $lease_detail->lease_id = $current_lease->id;
                            $lease_detail->is_used = true;
                            $lease_detail->start_date = $currentDate;
                            $lease_detail->end_date = $end_date;
                            $lease_detail->save();
                            $current_lease->total += $proratedLease;
                            $current_lease->discount += $proratedLease;
                            $current_lease->save();
                            break;
                        }
                    }
                } else {
                    $lease_detail = new SellerLeaseDetail;
                    $lease_detail->role_id = $roleId;
                    $lease_detail->amount = 0;
                    $lease_detail->lease_id = $current_lease->id;
                    $lease_detail->is_used = true;
                    $lease_detail->start_date = $start_date;
                    $lease_detail->end_date = $end_date;
                    $lease_detail->save();
                }
                $staff = new Staff;
                $staff->user_id = $user->id;
                $staff->created_by = $user->owner_id;
                $staff->role_id = $roleId;
                $role = Role::findOrFail($roleId);
                $user->assignRole($role->name);
                $staff->save();
            }

            if ($staff->save()) {
                $user->syncRoles(Role::whereIn('id', $request->role_id)->pluck('name'));
                flash(__('staff.Staff has been updated successfully'))->success();
                return redirect()->route('seller.staffs.index');
            }

            flash(__('staff.Something went wrong'))->error();
            return back();
        } catch (Exception $e) {
            Log::error('Error updating staff: ' . $e->getMessage());
            flash(__('staff.Something went wrong') . $e->getMessage())->error();
            return back();
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
        try {
            $currentDate = Carbon::now();
            $startDay = (clone $currentDate);
            $endDay = (clone $currentDate)->subDay(1);
            $current_lease = SellerLease::where('vendor_id', Auth::user()->owner_id)
                                        ->where('start_date', '<=', $startDay)
                                        ->where('end_date', '>=', $endDay)
                                        ->first();

            if (!$current_lease) {
                throw new Exception('Current lease not found');
            }

            $roles_id = SellerLeaseDetail::where('lease_id', $current_lease->id)->orderBy('is_used')->get();
            $staff = Staff::find($id);

            if (!$staff) {
                throw new Exception('Staff not found');
            }

            $staffs = Staff::where('user_id', $staff->user->id)->get();

            foreach ($staffs as $key => $st) {
                $staff_role = SellerLeaseDetail::where('lease_id', $current_lease->id)
                                               ->where('role_id', $st->role_id)
                                               ->where('is_used', true)
                                               ->where('amount', '!=', 0)
                                               ->first();
                if ($staff_role) {
                    $current_lease->total -= $staff_role->amount;
                    $current_lease->discount -= $staff_role->amount;
                    $current_lease->save();
                    $staff_role->delete();
                } else {
                    $staff_role = SellerLeaseDetail::where('lease_id', $current_lease->id)
                                                   ->where('role_id', $st->role_id)
                                                   ->where('is_used', true)
                                                   ->first();
                    if ($staff_role) {
                        $staff_role->is_used = 0;
                        $staff_role->save();
                    }
                }
                $st->delete();
            }

            if (User::destroy($staff->user->id)) {
                flash(__('staff.Staff has been deleted successfully'))->success();
                return redirect()->route('seller.staffs.index');
            }

            flash(__('staff.Something went wrong'))->error();
            return back();
        } catch (Exception $e) {
            // Log the error message for debugging purposes
            Log::error('Error deleting staff: ' . $e->getMessage());

            flash(__('staff.Something went wrong') . $e->getMessage())->error();
            return back();
        }
    }


    public function checkRole(Request $request)
    {
        $amount=0;
        $roles=0;
        $currentDate = Carbon::now();
        $startDay = (clone $currentDate);
        $endDay = (clone $currentDate)->subDay(1);
        $selectedRoles = $request->input('roles');
        $current_lease = SellerLease::where('vendor_id',Auth::user()->owner_id)->where('start_date', '<=', $startDay)
            ->where('end_date', '>=', $endDay)->first();
        $roles_id=SellerLeaseDetail::where('lease_id',$current_lease->id)->orderBy('is_used')->get();
        if (isset($request->staff_id)) {
            $staff=Staff::where('id',$request->staff_id)->first();
            $staffs=Staff::where('user_id',$staff->user->id)->get();
        }
        if($request->roles!=null){
            foreach ($request->roles as $role_id) {
                //dd($staffs->pluck('role_id')->contains($role_id),$staffs->pluck('role_id'),$role_id);
                foreach ($roles_id as $role_detail) {
                    if(isset($staffs) && $staffs->pluck('role_id')->contains($role_id)){
                        break;
                    }
                    // Check if the role_id matches and is not marked as used
                    elseif ($role_detail->role_id == $role_id && $role_detail->is_used == false){
                        break;
                    }
                    elseif ($role_detail->role_id == $role_id && $role_detail->is_used == true) {
                        $cycleEnd = Carbon::parse($current_lease->end_date)->endOfDay();
                        $daysToCycleEnd = $cycleEnd->diffInDays($currentDate)+1;

                    // Calculate the lease cycle days
                        $start_date = Carbon::parse($current_lease->start_date);
                        $end_date = Carbon::parse($current_lease->end_date);
                        $leaseCycleDays = $start_date->diffInDays($end_date)+1;

                        $proratedLease = (10 * $daysToCycleEnd) / $leaseCycleDays;
                        $amount+= $proratedLease ;
                        $roles += 1 ;
                        break;
                    }
                }
            }
        }

        if($amount>0){
            return response()->json(['isUsed' => 1,'message' => __('staff.Great choice! Adding this role now is on us until the end of this month. Starting next month,an additional AED ').' '. number_format($amount,2) .' '.__('staff.will apply with your eShop lease. We\'re excited to see your team grow!')]);

        }
    }
}

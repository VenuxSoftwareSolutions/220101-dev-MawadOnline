<?php

namespace App\Http\Controllers\Seller;

use Auth;
// use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\RoleTranslation;
use App\Models\RoleHasPermissions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SellerRoleController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:seller_view_staff_roles'])->only('index');
        $this->middleware(['permission:seller_add_staff_role'])->only('create');
        $this->middleware(['permission:seller_edit_staff_role'])->only('edit');
        $this->middleware(['permission:seller_delete_staff_role'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$roles=Auth::user()->roles();
        //$roles = Role::where('id', '!=', 1)->where('role_type',1)->paginate(10);
        $roles = Role::where('role_type',1)->whereIn('created_by',[1,Auth::user()->owner_id])->paginate(10);
        return view('seller.staff.staff_roles.index', compact('roles'));

        // $roles = Role::paginate(10);
        // return view('seller.staff.staff_roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $user = Auth::user();
        // $role=Staff::where('user_id',$user->id)->first()->role;
        // $permission_groups=$role->permissions->groupBy('section');
        //$permission_groups=Permission::whereIn('id',$permissions)->get()->groupBy('section');
        //$permission_groups = $role->permissions->groupBy('section');
        //dd($permission_groups);
        return view('seller.staff.staff_roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!auth('web')->user()->can('seller_add_staff_role'), Response::HTTP_FORBIDDEN, 'ACCESS FORBIDDEN');
        // dd($request->permissions);
        $role = Role::create([
            'name' => $request->name,
            'role_type' => 1,
            'created_by' => Auth::user()->owner_id,
            'description' => $request->description
        ]);
        $role->givePermissionTo($request->permissions);

        // $role = Role::create([
        //     'name' => $request->name,
        //     'description' => $request->description
        // ]);
        // $role->givePermissionTo($request->permissions);
        // $role->created_by=Auth::user()->id;
        // $role->role_type=1;
        // $role->save();

        // $role_translation = RoleTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'role_id' => $role->id]);
        // $role_translation->name = $request->name;
        // $role_translation->save();

        flash(translate('New Role has been added successfully'))->success();
        return redirect()->route('seller.roles.index');
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
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $role = Role::findOrFail($id);
        return view('seller.staff.staff_roles.edit', compact('role', 'lang'));
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
        abort_if(!auth('web')->user()->can('seller_edit_staff_role'), Response::HTTP_FORBIDDEN, 'ACCESS FORBIDDEN');

        $role = Role::findOrFail($id);
        if($role->created_by != Auth::user()->owner_id || $role->role_type == 0){
            return back();
        }

        $role->name = $request->name;
        $role->description= $request->description ;
        $role->syncPermissions($request->permissions);
        $role->save();

        // Role Translation
        // $role_translation = RoleTranslation::firstOrNew(['lang' => $request->lang, 'role_id' => $role->id]);
        // $role_translation->name = $request->name;
        // $role_translation->save();

        flash(translate('Role has been updated successfully'))->success();
        return back();
        // return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RoleTranslation::where('role_id', $id)->delete();
        Role::destroy($id);
        flash(translate('Role has been deleted successfully'))->success();
        return redirect()->route('seller.roles.index');
    }

    // public function add_permission(Request $request)
    // {
    //     $permission = Permission::create(['name' => $request->name, 'section' => $request->parent]);
    //     return redirect()->route('seller.roles.index');
    // }

}

<?php

namespace Database\seeds;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddSellerRoleToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $role= Role::where('name','seller')->first();
        // $users= User::where('user_type','seller')->where('owner_id',null)->get();
        // foreach ($users as $user) {
        //     $user->owner_id = $user->id;
        //     $user->assignRole('seller');
        //     $user->save();
        //     $staff= Staff::where('user_id',$user->id)->first();
        //     if (!$staff || $staff->role_id != $role->id) {
        //         $newstaff = new Staff ;
        //         $newstaff->user_id = $user->id ;
        //         $newstaff->role_id = $role->id ;
        //         $newstaff->save();
        //     }
        // }
    }
}

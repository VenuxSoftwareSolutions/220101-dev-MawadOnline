<?php

namespace Database\seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\Seller;
use App\Models\PayoutInformation;
use App\Models\BusinessInformation;

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

        $users= User::where('user_type','seller')->whereColumn('id', 'owner_id')->get();

        foreach($users as $user){
            $business= BusinessInformation::where('user_id',$user->id)->first();
            $payoutInformation = PayoutInformation::where('user_id',$user->id)->first();
            if($business && $payoutInformation){
                $shop = Seller::updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    [
                        'bank_name' => $payoutInformation->bank_name,
                        'bank_acc_name' => $payoutInformation->account_name,
                        'bank_acc_no' => $payoutInformation->account_number,
                        'bank_routing_no' => $payoutInformation->iban,
                        'verification_status' => 1,
                    ]
                );

                $seller = Shop::updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    [
                        'name' => $user->name,
                        'verification_status' => 1,
                        'slug' => $business->trade_name,
                        'meta_title' => $business->eshop_name,
                        'meta_description' => $business->eshop_desc,
                        'bank_name' => $payoutInformation->bank_name,
                        'bank_acc_name' => $payoutInformation->account_name,
                        'bank_acc_no' => $payoutInformation->account_number,
                        'bank_routing_no' => $payoutInformation->iban,
                    ]
                );
            }

        }
    }
}

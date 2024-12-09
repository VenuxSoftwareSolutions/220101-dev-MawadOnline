<?php

namespace App\Console;

use App\Models\SellerLease;
use App\Models\SellerLeaseDetail;
use App\Models\SellerPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ProcessStockQuantityReservationCheck;
use App\Jobs\CountdownNotificationJob;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sitemap:generate')->daily();

        $schedule->call(function () {
            // Logic to delete rejected vendors older than 30 days
            User::where('status', 'Rejected')->where('user_type', 'seller')
                ->where('updated_at', '<=', now()->subDays(30))
                ->delete();
        })->daily();

        $schedule->call(function () {
            $currentDate = Carbon::now();

            $users = User::where('user_type', 'seller')
                ->whereColumn('id', 'owner_id')
                ->get();

            foreach ($users as $user) {
                $current_lease = SellerLease::where('vendor_id', $user->owner_id)
                    ->where('start_date', '<=', $currentDate)
                    ->where('end_date', '>=', $currentDate)
                    ->first();

                if (! $current_lease) {
                    $last_lease = SellerLease::where('vendor_id', $user->owner_id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($last_lease) {
                        if ($user->status == 'Enabled') {
                            // Calculate the start date of the lease cycle
                            $startDate = Carbon::parse($last_lease->end_date)->addDay();
                            // Calculate the end date of the lease cycle
                            $endDate = $startDate->copy()->addMonth()->subDay();

                            $package = SellerPackage::find('4');

                            $seller_lease = new SellerLease;
                            $seller_lease->vendor_id = $user->id;
                            $seller_lease->package_id = 4;
                            $seller_lease->start_date = $startDate->format('Y-m-d');
                            $seller_lease->end_date = $endDate->format('Y-m-d');
                            $seller_lease->total = $package->amount;
                            $seller_lease->discount = $package->amount;
                            $seller_lease->save();

                            $lease_details = SellerLeaseDetail::where('lease_id', $last_lease->id)->get();

                            foreach ($lease_details as $detail) {
                                $lease_detail = new SellerLeaseDetail;
                                $lease_detail->role_id = $detail->role_id;
                                if ($detail->amount > 0) {
                                    $lease_detail->amount = 10;
                                } else {
                                    $lease_detail->amount = 0;
                                }

                                $lease_detail->lease_id = $seller_lease->id;
                                $lease_detail->is_used = true;
                                $lease_detail->start_date = $startDate->format('Y-m-d');
                                $lease_detail->end_date = $endDate->format('Y-m-d');
                                $lease_detail->save();

                                $seller_lease->total += $lease_detail->amount;
                                $seller_lease->discount += $lease_detail->amount;
                                $seller_lease->save();

                            }
                        }
                    }
                }
            }
        })->everyMinute();

        $schedule->job(new ProcessStockQuantityReservationCheck)
            ->everyThirtyMinutes();
        $schedule->job(new CountdownNotificationJob)->everyTwoHours()->appendOutputTo(storage_path('/logs/schedule.log'));

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

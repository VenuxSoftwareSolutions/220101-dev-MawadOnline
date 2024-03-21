<?php

namespace App\Http\Controllers\Seller;

use Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function index() {
        $owner = User::find(auth()->user()->owner_id);
        $notifications = $owner->notifications()->paginate(15);
        $owner->unreadNotifications->markAsRead();

        return view('seller.notification.index', compact('notifications'));
    }
}

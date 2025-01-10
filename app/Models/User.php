<?php

namespace App\Models;

use App\Notifications\EmailVerificationNotification;
use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasRoles, Notifiable;

    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'user_type', 'first_name', 'last_name', 'password', 'address', 'city', 'postal_code', 'phone', 'country', 'provider_id', 'email_verified_at', 'verification_code',
    ];

    protected $dates = [
        'approved_at', 'last_status_update',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function affiliate_user()
    {
        return $this->hasOne(AffiliateUser::class);
    }

    public function affiliate_withdraw_request()
    {
        return $this->hasMany(AffiliateWithdrawRequest::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function seller_orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function seller_sales()
    {
        return $this->hasMany(OrderDetail::class, 'seller_id');
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)->orderBy('created_at', 'desc');
    }

    public function club_point()
    {
        return $this->hasOne(ClubPoint::class);
    }

    public function customer_package()
    {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function vendor_status_history()
    {
        return $this->hasMany(VendorStatusHistory::class, 'vendor_id');
    }

    public function getSuspendedStatusHistory()
    {
        // Retrieve the latest vendor status history where the status is "Suspended"
        return Auth::user()->vendor_status_history()
            ->where('status', 'Suspended')
            ->latest()
            ->first();
    }

    public function customer_package_payments()
    {
        return $this->hasMany(CustomerPackagePayment::class);
    }

    public function customer_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function seller_package_payments()
    {
        return $this->hasMany(SellerPackagePayment::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function product_bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function product_queries()
    {
        return $this->hasMany(ProductQuery::class, 'customer_id');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function userCoupon()
    {
        return $this->hasOne(UserCoupon::class);
    }

    public function business_information()
    {
        return $this->hasOne(BusinessInformation::class);
    }

    public function contact_people()
    {
        return $this->hasOne(ContactPerson::class);
    }

    public function payout_information()
    {
        return $this->hasOne(PayoutInformation::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function getStaff()
    {
        return $this->hasMany(User::class, 'owner_id', 'id')->where('id', '!=', $this->id);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($user) {
            // Check if the status attribute is being updated
            if ($user->isDirty('status')) {
                // Update the last_status_update attribute with the current timestamp
                $user->last_status_update = now();
            }
        });
    }

    public function leases()
    {
        return $this->hasMany(SellerLease::class);
    }

    public function checkProposedChanges()
    {
        $proposedChange = ProposedPayoutChange::where('user_id', $this->id)->latest()->first();

        return $proposedChange && $proposedChange->status == 'pending';
    }
}

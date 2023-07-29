<?php

namespace App\Models;

use App\Models\Invoice\Invoice;
use App\Models\setting\Country;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\ProfileBusiness;
use App\Models\MultAuth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class User extends Authenticatable implements JWTSubject
{


    use HasApiTokens, HasFactory, Notifiable;
    // use SoftDeletes ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'role_id', 'profile_business_id',
        'phone_number_code_manager_id',
        'phone_number_manager',
        'email', 'full_name', 'avatar', 'password',
        'nationality_id', 'is_enable',
        'enable_bell_sound', 'confirm_email', 'confirm_phone', 'batch_invoices',
        'deposits', 'payment_links', 'profile',
        'users', 'refund', 'show_all_invoices', 'customers', 'invoices', 'products', 'commissions', 'account_statements',
        'orders', 'suppliers', 'notification_create_invoice', 'notification_invoice_paid', 'notification_new_order',
        'notification_create_batch_invoice', 'notification_deposit', 'notification_create_recurring_invoice',
        'notification_refund_transfered', 'notification_notifications_serviceRequest', 'notification_notifications_hourly_deposit_rejected',
        'notification_approve_vendor_account', 'notification_create_shipping_invoice',

    ];

    // protected $date = ['delete_at'] ;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',

        // 'profile_business_id',
        // 'phone_number_code_manager_id',
        // 'nationality_id' ,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function profileBusiness()
    {
        return $this->hasOne(ProfileBusiness::class, 'id', 'profile_business_id');
    }
    public function userToken()
    {
        return $this->hasOne(UserToken::class, 'user_id', 'id')->latest();
        // order by by how ever you need it ordered to get the latest
    }

    public function phoneNumberCode()
    {
        return $this->hasOne(Country::class, 'id', 'phone_number_code_manager_id');
    }
    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'manager_user_id', 'id');
    }


    public function nationality()
    {
        return $this->hasOne(Country::class, 'id', 'nationality_id');
    }
    public function userRole()
    {
        return $this->hasOne(UserRole::class, 'id', 'role_id');
    }

    public function multAuth()
    {
        return $this->hasOne(MultAuth::class, 'id_admin_or_user',  'id');
    }


    public function hasability($premission)
    {
        $id = auth()->user()->id;
        $users = DB::table('users')->find($id);
        if ($users) {
            if ($users->$premission == true)
                return true;
            else
                return false;
        } else
            return false;
    }
}

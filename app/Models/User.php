<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use App\Traits\HasFormattedTimestamps;

class User extends Authenticatable
{
    use LaratrustUserTrait, HasFormattedTimestamps;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime','payload' => 'array',
    ];
    
    public const BULK_ACTIVATION = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    public const CHART_PRIORITY_FIRST = 0;
    public const CHART_PRIORITY_SECOND = 1;
    public const CHART_PRIORITY_THIRD = 3;
    public const CHART_PRIORITY_FOURTH = 5;
    public const CHART_PRIORITY_SIXTH = 9;
    
    public const CHART_LIMIT_FIRST = 0;
    public const CHART_LIMIT_SECOND = 1;
    public const CHART_LIMIT_THIRD = 2;
    public const CHART_LIMIT_FOURTH = 3;
    public const CHART_LIMIT_FIFTH = 4;

    public const DX_LIMIT_FIRST = 0;
    public const DX_LIMIT_SECOND = 1;
    public const DX_LIMIT_THIRD = 2;
    public const DX_LIMIT_FOURTH = 3;
    public const DX_LIMIT_FIFTH = 4;

    public const PARKED_LIMIT_0 = 0;
    public const PARKED_LIMIT_5 = 1;
    public const PARKED_LIMIT_10 = 2;
    public const PARKED_LIMIT_15 = 3;
    public const PARKED_LIMIT_20 = 4;

    public const PREFIX = "USR";

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
    public function getAvatarAttribute($value)
    {
        $avatar = !is_null($value) ? asset('storage/backend/users/'.$value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($avatar);
        if (\Str::contains(request()->url(), '/api/vi')) {
            return asset($avatar);
        }
        return $avatar;
    }
    public const STATUSES = [
        "0" => ['label' =>'Inactive','color' => 'danger'],
        "1" => ['label' =>'Active','color' => 'success'],
    ];
    public const PARKED_LIMITS = [
        "0" => ['label' =>0,'color' => 'danger'],
        "1" => ['label' =>5,'color' => 'warning'],
        "2" => ['label' =>10,'color' => 'secondary'],
        "3" => ['label' =>15,'color' => 'primary'],
        "4" => ['label' =>20,'color' => 'success'],
    ];
    public const CHART_PRIORITIES = [
        "0" => ['value'=>"1",'percent'=>'100%','label' =>'100% (Every Entry Should be Audited)','color' => 'success'],
        "1" => ['value'=>"2",'percent'=>'50%','label' =>'50% (Every Second Entry Should be Audited)','color' => 'primary'],
        "3" => ['value'=>"4",'percent'=>'25%','label' =>'25% (Every Fourth Entry Should be Audited)','color' => 'info'],
        "5" => ['value'=>"6",'percent'=>'15%','label' =>'15% (Every Sixth Entry Should be Audited)','color' => 'secondary'],
        "9" => ['value'=>"10",'percent'=>'7%','label' =>'7% (Every 10th Entry Should be Audited)','color' => 'danger'],
    ];

    
    public const CHART_LIMITS = [
        "0" => ['min'=>"0",'max'=>'25','label' =>'0 - 25','color' => 'danger'],
        "1" => ['min'=>"26",'max'=>'40','label' =>'26 - 40','color' => 'primary'],
        "2" => ['min'=>"41",'max'=>'100','label' =>'41 - 100','color' => 'info'],
        "3" => ['min'=>"101",'max'=>'250','label' =>'101 - 250','color' => 'secondary'],
        "4" => ['min'=>"250",'max'=>'5000','label' =>'250 - More','color' => 'success'],
    ];
    public const DX_LIMITS = [
        "0" => ['min'=>"0",'max'=>'2','label' =>'Less 2 Dx','color' => 'danger'],
        "1" => ['min'=>"3",'max'=>'10','label' =>'3 - 10 Dx','color' => 'primary'],
        "2" => ['min'=>"11",'max'=>'25','label' =>'11 -25 Dx','color' => 'info'],
        "3" => ['min'=>"25",'max'=>'50','label' =>'25 - 50 Dx','color' => 'secondary'],
        "4" => ['min'=>"50",'max'=>'5000','label' =>'50 - More Dx','color' => 'success'],
    ];
    protected $appends = [
        'full_name' , 'name'
      ];
    protected function statusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn ($value) =>  (object)self::STATUSES[$this->status],
        );
    }
    public function ekyStatus()
    {
        return $this->belongsTo(UserKyc::class, 'status');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function walletLogs()
    {
        return $this->hasMany(WalletLog::class);
    }
    public function pendingWalletRequest()
    {
        return $this->hasMany(WalletLog::class)->whereStatus(0);
    }
    public function payoutDetails()
    {
        return $this->hasMany(PayoutDetail::class, 'user_id', 'id');
    }
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }
    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'user_subscriptions');
    }
    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }
    public function wishlists()
    {
        return $this->hasMany(MyWishlist::class);
    }
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    public function kycs()
    {
        return $this->hasMany(UserKyc::class);
    }
    public function logs()
    {
        return $this->hasMany(UserLog::class);
    }
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    public function userNotes()
    {
        return $this->hasMany(UserNote::class, 'type_id');
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'type_id')->whereType('User');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
    public function getRoleNameAttribute()
    {
        if (!empty($this->roles)) {
            return $this->roles[0]->display_name;
        } else {
            return "No Role" ;
        }
    }
    public function getFullNameAttribute()
    {
        return ucwords($this->first_name.' '.$this->last_name);
    }
    public function getNameAttribute()
    {
        return ucwords($this->first_name.' '.$this->last_name);
    }

    public function getPrefix()
    {
        return "#USR".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function scopeWhereRoleIsNot($query, $role = '', $team = null)
    {
        return $query->whereHas(
            'roles',
            function ($roleQuery) use ($role, $team) {
                $roleQuery->whereNotIn('name', $role);
                if (!is_null($team)) {
                    $roleQuery->whereNotIn('team_id', $team->id);
                }
            }
        );
    }
    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string $value
     * @return string
     */
    public function setGoogle2faSecretAttribute($value)
    {
         $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        if ($value == null) {
            return null;
        }
        return decrypt($value);
    }
}

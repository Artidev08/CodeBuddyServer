<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use App\Models\Country;

class UserAddress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'user_addresses';
    protected $guarded = ['id'];
    protected $casts = [
        'details' => 'json'
    ];

    protected $defaultAddress = [
        "full_name"=>null,
        "number"=>null,
        "address"=>null,
        "address2"=>null,
        "country"=>null,
        "state"=>null,
        "city"=>null,
        "pincode"=>null,
        "type"=>null
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function country()
    {
        return $this->belongsTo(Country::class, 'details->country', 'id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'details->state', 'id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'details->city', 'id');
    }
}

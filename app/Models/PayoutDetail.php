<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class PayoutDetail extends Model
{
    use HasFactory;

    protected $table = 'payout_details';
    protected $guarded = ['id'];
    protected $casts = [
        'payload' => 'json'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
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
}

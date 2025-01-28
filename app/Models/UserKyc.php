<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserKyc extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_kycs';
    protected $guarded = ['id'];
    public function getFrontImageAttribute($value)
    {
        $frontImage = !is_null($value) ? asset($value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($frontImage);
        if (\Str::contains(request()->url(), '/api')) {
            return asset($frontImage);
        }
        return $frontImage;
    }
    public function getbackImageAttribute($value)
    {
        $backImage = !is_null($value) ? asset($value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($backImage);
        if (\Str::contains(request()->url(), '/api')) {
            return asset($backImage);
        }
        return $backImage;
    }
    public function getFaceWithDocAttribute($value)
    {
        $FaceWithDoc = !is_null($value) ? asset($value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($FaceWithDoc);
        if (\Str::contains(request()->url(), '/api')) {
            return asset($FaceWithDoc);
        }
        return $FaceWithDoc;
    }
}

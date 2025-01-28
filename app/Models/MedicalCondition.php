<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCondition extends Model
{
    use HasFactory;

    public const PUBLISHED = 1;
    public const UNPUBLISHED = 0;

    protected $guarded = ['id'];
    
    public function getPrefix()
    {
        return "#MDC".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    protected $casts = [
        'hcc' => 'object'
    ];


    public function scopePublished($builder)
    {
        return $builder->where('is_published', self::PUBLISHED);
    }

    public function medias(){
        return Media::where('type_id', $this->id)->where('type', "MedicalCondition")->get();
    }

    public function versions(){
        return $this->hasMany(MedCodeVersion::class,'medical_id','id');
    }
}

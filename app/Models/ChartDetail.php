<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;

class ChartDetail extends Model
{
    public const NOT_DELIVERED = 0;
    public const DELIVERED = 1;
    protected $casts = [
        'hcc' => 'object',
        'native_hcc' => 'object'
    ];
    public const IS_DELIVERED = [
        "0" => ['label' =>'Not Delivered','color' => 'danger'],
        "1" => ['label' =>'Delivered','color' => 'success'],
    ];
    use HasFactory,HasFormattedTimestamps;
    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#CE".$this->id;
    }
    public function doctor(){
        return $this->belongsTo(Category::class, 'doctor_id','id');  
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');  
    }

    public function chunk(){
        return $this->belongsTo(ChartChunk::class, 'chunk_id','id');  
    }
}

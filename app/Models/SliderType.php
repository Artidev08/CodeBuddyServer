<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SliderType extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'slider_types';
    protected $guarded = ['id'];

    public const STATUS_PUBLISHED = 1;
    public const STATUS_UNPUBLISHED = 0;
    
    public function getPrefix()
    {
        return "#ST".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class, 'slider_type_id');
    }
}

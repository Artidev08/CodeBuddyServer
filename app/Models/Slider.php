<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Slider extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory,SoftDeletes;
    protected $table = 'sliders';
    protected $guarded = ['id'];
    protected $appends = [
        'image'
    ];

    public const TYPE_PLAIN_TEXT = 0;
    public const TYPE_RICH_TEXT = 1;
    public const TYPE_WEBPAGE_LINK = 2;
    public const TYPE_IMAGE_LINK = 3;
    public const TYPE_DOCUMENT_LINK = 4;

    public const TYPES = [
        "1" => ['label' =>'Plain Text','color' => 'warning'],
        "2" => ['label' =>'Rich Text','color' => 'info'],
        "3" => ['label' =>'Webpage Link','color' => 'secondary'],
        "4" => ['label' =>'Image Link','color' => 'primary'],
        "5" => ['label' =>'Document Link','color' => 'danger'],
    ];
    
    public function getPrefix()
    {
        return "#SL".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('image');
    }
}

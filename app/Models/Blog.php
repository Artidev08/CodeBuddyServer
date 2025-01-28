<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    use InteractsWithMedia;
    
    protected $table = 'blogs';
    protected $guarded = [];
    protected $casts = ['meta'=>'array'];

    public function getPrefix()
    {
        return "#BLG".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getDescriptionBannerAttribute($value)
    {
        $descriptionBanner = !is_null($value) ? ('storage/site/blog/'.$value) : asset('storage/backend/img/placeholder.jpg');
        if (\Str::contains(request()->url(), '/api')) {
            return asset($descriptionBanner);
        }
        return $descriptionBanner;
    }
}

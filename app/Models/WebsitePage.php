<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class WebsitePage extends Model implements HasMedia
{
    use HasFactory,HasFormattedTimestamps;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $casts = ['meta' => 'array'];
    protected $table = 'website_pages';
    protected $guarded = ['id'];
}

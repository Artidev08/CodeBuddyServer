<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class SeoTag extends Model
{
    use HasFactory,SoftDeletes,HasFormattedTimestamps;
    protected $table = 'seo_tags';
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#ST".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}

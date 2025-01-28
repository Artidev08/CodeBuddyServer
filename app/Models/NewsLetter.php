<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsLetter extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'news_letters';
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#NL".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}

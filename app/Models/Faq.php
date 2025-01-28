<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'faqs';
    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#FQ".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}

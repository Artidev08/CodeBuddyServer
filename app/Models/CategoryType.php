<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class CategoryType extends Model
{
    use HasFactory,SoftDeletes;
    use HasFormattedTimestamps;

    
    protected $table = 'category_types';
    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#MCT".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function categories()
    {
        return $this->hasMany(Category::class, 'category_type_id');
    }
}

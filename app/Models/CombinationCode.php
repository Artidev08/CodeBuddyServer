<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class CombinationCode extends Model
{
    use HasFactory,SoftDeletes;
    use HasFormattedTimestamps;

    protected $table = 'combination_codes';
    protected $guarded = ['id'];
    protected $casts = [
        'codes' => 'array'
    ];

    public function getPrefix()
    {
        return "#CC".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    
}

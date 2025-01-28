<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    
    protected $table = 'countries';
    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#CNT".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}

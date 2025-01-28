<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $table = 'states';
    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#ST".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}

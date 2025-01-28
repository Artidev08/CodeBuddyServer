<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    
    use HasFactory;
    // use SoftDeletes;
    protected $table = 'cities';
    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#CT".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}

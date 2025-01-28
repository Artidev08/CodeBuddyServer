<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $table = 'contacts';
    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'type_id');
    }
    public function getPrefix()
    {
        return "#CN".str_replace('_1', '', '_'.(1000 +$this->id));
    }
}

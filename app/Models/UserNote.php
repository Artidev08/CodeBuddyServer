<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNote extends Model
{
    use HasFactory,HasFormattedTimestamps,SoftDeletes;

    protected $table = 'user_notes';
    protected $guarded = ['id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'type_id');
    }
    public function getPrefix()
    {
        return "#UN".str_replace('_1', '', '_'.(1000 +$this->id));
    }
}

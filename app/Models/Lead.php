<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    protected $table = 'leads';
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#LD".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userNotes()
    {
        return $this->hasMany(UserNote::class, 'type_id');
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'type_id')->whereType('Lead');
    }
    public function source()
    {
        return $this->belongsTo(Category::class, 'lead_source_id', 'id');
    }
    public function leadType()
    {
        return $this->belongsTo(Category::class, 'lead_type_id', 'id');
    }
    public function status()
    {
        return $this->belongsTo(Category::class, 'lead_type_id', 'id');
    }
}

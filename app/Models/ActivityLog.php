<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;

class ActivityLog extends Model
{
    use HasFactory,HasFormattedTimestamps;

    protected $guarded = ['id'];
    protected $casts = [
        'payload' => 'array',
    ];
}

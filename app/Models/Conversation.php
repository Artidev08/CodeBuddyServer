<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Conversation extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $table = 'conversations';
    protected $guarded = ['id'];

    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class, 'model_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}

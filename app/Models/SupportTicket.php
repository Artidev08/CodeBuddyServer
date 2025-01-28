<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasFormattedTimestamps;

class SupportTicket extends Model implements HasMedia
{
    use SoftDeletes ,InteractsWithMedia ,HasFactory,HasFormattedTimestamps;
    protected $table = 'support_tickets';
    protected $guarded = ['id'];

    public const STATUS_UNDER_WORKING = 0;
    public const STATUS_REPLY_RECEIVED = 1;
    public const STATUS_RESOLVED = 2;
    
    public const PRIORITY_LOW = 0;
    public const PRIORITY_MEDIUM = 1;
    public const PRIORITY_HIGH = 2;

    public const STATUSES = [
        "0" => ['label' =>'Under Working','color' => 'secondary'],
        "1" => ['label' =>'Reply Received','color' => 'info'],
        "2" => ['label' =>'Resolved','color' => 'success']
    ];

    public const PRIORITIES = [
        "0" => ['label' =>'Low','color' => 'green'],
        "1" => ['label' =>'Medium','color' => 'yellow'],
        "2" => ['label' =>'High','color' => 'red']
    ];

    public function getPrefix()
    {
        return "#ST".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    
    protected function statusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn ($value) =>  (object)self::STATUSES[$this->status],
        );
    }
    protected function priorityParsed(): Attribute
    {
        return  Attribute::make(
            get: fn ($value) =>  (object)self::PRIORITIES[$this->priority??0],
        );
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'type_id')->orderBy('id', 'asc');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function medias()
    {
        return $this->hasMany(Media::class, 'modal_id', 'id')->where('model_type', 'App\Models\SupportTicket');
    }
}

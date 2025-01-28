<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class WebsiteEnquiry extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;

    protected $table = 'website_enquiries';
    protected $guarded = ['id'];

    public const STATUS_NEW = 0;
    public const STATUS_CONTACTED = 1;
    public const STATUS_CLOSED = 2;

    public const STATUSES = [
        "0" => ['label' =>'New','color' => 'info'],
        "1" => ['label' =>'Contacted','color' => 'success'],
        "2" => ['label' =>'Closed','color' => 'danger'],
    ];

    public function getPrefix()
    {
        return "#WE".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    protected function StatusParsed(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  (object)self :: STATUS[$this->status],
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class WalletLog extends Model
{
    use HasFactory,SoftDeletes,HasFormattedTimestamps;

    protected $table = 'wallet_logs';
    protected $guarded = ['id'];

    public const STATUS_REQUESTED = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_DECLINED = 2;

    public const STATUSES = [
        "0" => ['label' =>'Requested','color' => 'info'],
        "1" => ['label' =>'Accepted','color' => 'success'],
        "2" => ['label' =>'Declined','color' => 'danger'],
    ];
    protected function WalletStatusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn ($value) =>  (object)self::WALLET_STATUSES[$this->status],
        );
    }
    protected function statusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn ($value) =>  (object)self::STATUSES[$this->status],
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getPrefix()
    {
        return "#WLT".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}

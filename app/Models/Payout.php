<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class Payout extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    
    protected $table = 'payouts';
    protected $guarded = ['id'];
    protected $casts = [
        'bank_details' => 'json'
    ];
    public const STATUS_IN_REVIEW = 0;
    public const STATUS_PAID = 1;
    public const STATUS_REJECTED = 2;

    public const TYPE_BANK = 0;
    public const TYPE_UPI = 1;

    public const STATUSES = [
        "0" => ['label' =>'In Review','color' => 'warning'],
        "1" => ['label' =>'Approved & Paid','color' => 'success'],
        "2" => ['label' =>'Rejected','color' => 'danger'],
    ];

    protected function statusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn ($value) =>  (object)self :: STATUSES[$this->status],
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
    public function payoutDetails()
    {
        return $this->belongsTo(PayoutDetail::class);
    }
    public function getPrefix()
    {
        return "#PAY".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}

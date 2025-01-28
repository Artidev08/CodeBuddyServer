<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;
use App\Models\User;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    protected $table = 'orders';
    protected $guarded = ['id'];
    protected $casts = ['from'=>'array','to'=>'array','tax_data'=>'array'];
    
    public const STATUS_IDLE = 0;
    public const STATUS_PACKED = 1;
    public const STATUS_SHIPPED = 2;
    public const STATUS_DELIVERED = 3;
    public const STATUS_COMPLETED = 4;
    public const STATUS_CANCELLED = 5;
    public const STATUS_HOLD = 6;

    public const TAX_STRUCTURE = array('name' => "NO_TAX",'slap' => 0,'amount' => 0);

    public const STATUSES = [
        "0" => ['label' =>'Idle','color' => 'warning'],
        "1" => ['label' =>'Packed','color' => 'info'],
        "2" => ['label' =>'Shipped','color' => 'secondary'],
        "3" => ['label' =>'Delivered','color' => 'primary'],
        "4" => ['label' =>'Completed','color' => 'success'],
        "5" => ['label' =>'Cancelled','color' => 'danger'],
        "6" => ['label' =>'Hold','color' => 'dark'],
    ];

    public const PAYMENT_STATUS_UNPAID = 1;
    public const PAYMENT_STATUS_PAID = 2;
    public const PAYMENT_STATUS_REFUND_PROCESSING = 3;
    public const PAYMENT_STATUS_HOLD = 4;
    public const PAYMENT_STATUS_REFUNDED = 5;

    public const PAYMENT_STATUS = [
        "1" => ['label' =>'Unpaid','color' => 'danger'],
        "2" => ['label' =>'Paid','color' => 'success'],
        "3" => ['label' =>'Refund Processing','color' => 'primary'],
        "4" => ['label' =>'Hold','color' => 'warning'],
        "5" => ['label' =>'Refunded','color' => 'secondary'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'type_id', 'id');
    }

    protected function PaymentStatusParsed(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  (object)self :: PAYMENT_STATUS[$this->status],
        );
    }
    protected function statusParsed(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  (object)self :: STATUSES[$this->status],
        );
    }
    public function getAvatarAttribute($value)
    {
        $avatar = !is_null($value) ? asset('storage/backend/users/'.$value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($avatar);
        if (\Str::contains(request()->url(), '/api/vi')) {
            return asset($avatar);
        }
        return $avatar;
    }
    public function getPrefix()
    {
        return "#ORD".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }


    public function getFromCountryNameAttribute()
    {
        if ($this->from != null && array_key_exists('country', $this->from)) {
            return  Country::whereId($this->from['country'])->value('name');
        } else {
            return '';
        }
    }

    public function getFromStateNameAttribute()
    {
        if ($this->from != null && array_key_exists('state', $this->from)) {
            return  State::whereId($this->from['state'])->value('name');
        } else {
            return '';
        }
    }
    public function getFromCityNameAttribute()
    {
        if ($this->from != null && array_key_exists('city', $this->from)) {
            return  City::whereId($this->from['city'])->value('name');
        } else {
            return '';
        }
    }
    public function getToCountryNameAttribute()
    {
        if ($this->to != null && array_key_exists('country', $this->to)) {
            return  Country::whereId($this->to['country'])->value('name');
        } else {
            return '';
        }
    }

    public function getToStateNameAttribute()
    {
        if ($this->to != null && array_key_exists('state', $this->to)) {
            return  State::whereId($this->to['state'])->value('name');
        } else {
            return '';
        }
    }
    public function getToCityNameAttribute()
    {
        if ($this->to != null && array_key_exists('city', $this->to)) {
            return  City::whereId($this->to['city'])->value('name');
        } else {
            return '';
        }
    }
}

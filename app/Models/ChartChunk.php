<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartChunk extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#CN".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    protected $casts = ['payload' => 'array'];
    public const STATUS_IN_QUEUE = 0;
    public const STATUS_PROCESSING = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_FAILED = 3;

    public const STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'info'],
        "1" => ['label' =>'Processing','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];


    public const ENTRY_SYNC_STATUS_IN_QUEUE = 0;
    public const ENTRY_SYNC_STATUS_PROCESSING = 1;
    public const ENTRY_SYNC_STATUS_COMPLETED = 2;
    public const ENTRY_SYNC_STATUS_FAILED = 3;

    public const ENTRY_SYNC_STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'info'],
        "1" => ['label' =>'Processing','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];

    public const MC_SYNC_STATUS_IN_QUEUE = 0;
    public const MC_SYNC_STATUS_PROCESSING = 1;
    public const MC_SYNC_STATUS_COMPLETED = 2;
    public const MC_SYNC_STATUS_FAILED = 3;

    public const MC_SYNC_STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'info'],
        "1" => ['label' =>'Processing','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];

    public const NATIVE_SYNC_STATUS_IN_QUEUE = 0;
    public const NATIVE_SYNC_STATUS_PROCESSING = 1;
    public const NATIVE_SYNC_STATUS_COMPLETED = 2;
    public const NATIVE_SYNC_STATUS_FAILED = 3;

    public const NATIVE_SYNC_STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'info'],
        "1" => ['label' =>'Processing','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];

    public const HCC_SYNC_STATUS_IN_QUEUE = 0;
    public const HCC_SYNC_STATUS_PROCESSING = 1;
    public const HCC_SYNC_STATUS_COMPLETED = 2;
    public const HCC_SYNC_STATUS_FAILED = 3;

    public const HCC_SYNC_STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'info'],
        "1" => ['label' =>'Processing','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];

    public const LOCATION_SYNC_STATUS_IN_QUEUE = 0;
    public const LOCATION_SYNC_STATUS_PROCESSING = 1;
    public const LOCATION_SYNC_STATUS_COMPLETED = 2;
    public const LOCATION_SYNC_STATUS_FAILED = 3;

    public const LOCATION_SYNC_STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'info'],
        "1" => ['label' =>'Processing','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];

    public const RT_SYNC_STATUS_IN_QUEUE = 0;
    public const RT_SYNC_STATUS_PROCESSING = 1;
    public const RT_SYNC_STATUS_COMPLETED = 2;
    public const RT_SYNC_STATUS_FAILED = 3;

    public const RT_SYNC_STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'info'],
        "1" => ['label' =>'Processing','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($chart) {
            // Remove chartDetails
            if ($chart->entries->isNotEmpty()) {
                $chart->entries()->delete();
            }
        });
    }
    public function entries(){
        return $this->hasMany(ChartDetail::class,'chunk_id','id');
    }

    public function chart(){
        return $this->belongsTo(Chart::class,'chart_id','id');
    }
}

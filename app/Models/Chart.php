<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasFormattedTimestamps;

class Chart extends Model implements HasMedia
{
    use HasFactory,HasFormattedTimestamps;
    use InteractsWithMedia;
    protected $guarded = ['id'];
    protected $appends = [
        'pdf_url'
      ];

    public function getPrefix()
    {
        return "#CH".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public const STATUS_PENDING = 0;
    public const STATUS_OPEN = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_PARKED = 3;
    public const STATUS_REJECTED = 4;
    public const STATUS_REOPEN = 5;
    public const STATUS_AUDITED = 6;

    public const EXTRACT_Not_REQUIRED = 0;
    public const EXTRACT_PENDING = 1;
    public const EXTRACT_COMPLETED = 2;
    public const EXTRACT_SCHEDULED = 3;
    public const EXTRACT_FAILED = 4;

    public const IS_EXTRACT = [
        "0" => ['label' =>'Not Required','color' => 'danger'],
        "1" => ['label' =>'Pending','color' => 'warning'],
        "2" => ['label' =>'Extracted','color' => 'success'],
        "3" => ['label' =>'Scheduled','color' => 'info'],
        "4" => ['label' =>'Failed','color' => 'danger'],
    ];

    public const FLOW_TYPE_AUTOMATIC = 0;
    public const FLOW_TYPE_MANUAL = 1;

    public const FLOW_TYPE = [
        "0" => ['label' =>'Automatic','color' => 'success'],
        "1" => ['label' =>'Manual','color' => 'danger'],
    ];

    public const STATUSES = [
        "0" => ['label' =>'Pending','color' => 'info'],
        "1" => ['label' =>'Open','color' => 'primary'],
        "2" => ['label' =>'Completed','color' => 'success'],
        "3" => ['label' =>'Parked','color' => 'secondary'],
        "4" => ['label' =>'Rejected','color' => 'danger'],
        "5" => ['label' =>'Reopen','color' => 'warning'],
        "6" => ['label' =>'Audited','color' => 'secondary'],
    ];


    public const CHUNK_PENDING = 0;
    public const CHUNK_COMPLETED = 1;
    public const CHUNK_SCHEDULED = 2;
    public const CHUNK_FAILED = 3;

    public const IS_CHUNK = [
        "0" => ['label' =>'Pending','color' => 'warning'],
        "1" => ['label' =>'Chunked','color' => 'success'],
        "2" => ['label' =>'Scheduled','color' => 'info'],
        "3" => ['label' =>'Failed','color' => 'danger'],
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($chart) {
            // Remove Media or PDF
            if ($chart->getMedia('pdf')->isNotEmpty()) {
                $chart->clearMediaCollection('pdf');
            }

            // Remove chartDetails
            if ($chart->entries->isNotEmpty()) {
                $chart->entries()->delete();
            }

            // Remove ChartChunks
            if ($chart->chunks->isNotEmpty()) {
                $chart->chunks()->delete();
            }

            // Remove Audit Logs
            if ($chart->logs->isNotEmpty()) {
                $chart->logs()->delete();
            }

        });
    }


    public function entry(){
        return $this->belongsTo(User::class,'entry_user_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }
    public function auditor(){
        return $this->belongsTo(User::class,'auditor_id','id');
    }
    public function logs(){
        return $this->hasMany(ActivityLog::class,'related_id','id')
        ->where('related_type',Chart::class);
    }
    public function entries(){
        return $this->hasMany(ChartDetail::class,'chart_id','id');
    }
    public function chunks(){
        return $this->hasMany(ChartChunk::class,'chart_id','id');
    }
    public function getPdfUrlAttribute(){
        return $this->getFirstMediaUrl('pdf');
    }
}

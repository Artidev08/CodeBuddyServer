<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class ProjectEntry extends Model
{

    public const STATUS_IN_QUEUE = 0;
    public const STATUS_READY = 1;

    public const STATUSES = [
        "0" => ['label' =>'In Queue','color' => 'danger'],
        "1" => ['label' =>'Ready','color' => 'success'],
    ];
    use HasFactory,SoftDeletes;
    use HasFormattedTimestamps;

    protected $table = 'project_entries';
    protected $guarded = ['id'];
    protected $casts = [
        'criteria_payload' => 'array'
    ];

    public function getPrefix()
    {
        return "#PE".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function  medicalCondition(){
        return  $this->belongsTo(MedicalCondition::class,'medical_condition_id','id');
    }   
}

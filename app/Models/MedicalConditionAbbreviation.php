<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalConditionAbbreviation extends Model
{
    use HasFactory;

    public const PUBLISHED = 1;
    public const UNPUBLISHED = 0;

    protected $guarded = ['id'];
    
    public function getPrefix()
    {
        return "#".str_replace('_', '', '_'.(000000 +$this->id));
    }

    protected $casts = [
        'hcc' => 'object'
    ];

    // Defining the boot method
    protected static function boot()
    {
        parent::boot();

        // Handle both creates and updates with the saving event.
        static::saving(function ($record) {
            $record->title = strtoupper($record->title) ?? '';
        });
    }

    public function scopePublished($builder)
    {
        return $builder->where('is_published', self::PUBLISHED);
    }

    public function medicalCondition(){
        return $this->belongsTo(MedicalCondition::class, 'medical_condition', 'id');
    }
    public function medicalCodeVersion(){
        return $this->belongsTo(MedCodeVersion::class, 'code_version', 'id');
    }
}

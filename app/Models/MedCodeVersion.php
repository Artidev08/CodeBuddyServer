<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedCodeVersion extends Model
{
    use HasFactory;
    use SoftDeletes; // Corrected the case here

    protected $guarded = [];
     public function getPrefix() {
        return "#VR".str_replace('_1','','_'.(100000 +$this->id));
    }

    protected $casts = [
        'hcc' => 'array'
    ];

    // Defining the boot method
    protected static function boot()
    {
        parent::boot();

        // Handle both creates and updates with the saving event.
        static::saving(function ($record) {
            $record->code_version = strtoupper($record->code_version) ?? '';
        });
    }
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'med_code_versions'; // Ensure the correct table name is specified
}


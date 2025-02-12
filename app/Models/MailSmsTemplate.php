<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class MailSmsTemplate extends Model
{
    use HasFactory,SoftDeletes;
    use HasFormattedTimestamps;

    protected $table = 'mail_sms_templates';
    protected $guarded = ['id'];
    protected $casts = [
        'variables' => 'array'
    ];
    public function getPrefix()
    {
        return "#MST".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}

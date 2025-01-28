<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParagraphContent extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    
    protected $table = 'paragraph_contents';
    protected $guarded = ['id'];
    public const TYPE_PLAIN_TEXT = 0;
    public const TYPE_RICH_TEXT = 1;
    public const TYPE_WEBPAGE_LINK = 2;
    public const TYPE_IMAGE_LINK = 3;
    public const TYPE_DOCUMENT_LINK = 4;

    public const TYPES = [
        "1" => ['label' =>'Plain Text','color' => 'warning'],
        "2" => ['label' =>'Rich Text','color' => 'info'],
        "3" => ['label' =>'Webpage Link','color' => 'secondary'],
        "4" => ['label' =>'Image Link','color' => 'primary'],
        "5" => ['label' =>'Document Link','color' => 'danger'],
    ];
    public function getPrefix()
    {
        return "#SCM".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function groupData(){
        return $this->belongsTo(Category::class,'group','id');
    }
    public function getGroupNameAttribute(){
        return $this->groupData->name??"";
    }
}

<?php
/**
 * Class Folder
 *
 * @category ZStarter
 *
 * @ref zCURD
 * @author  Defenzelite <hq@defenzelite.com>
 * @license https://www.defenzelite.com Defenzelite Private Limited
 * @version <zStarter: 1.1.0>
 * @link    https://www.defenzelite.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
 



class Folder extends Model {
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;    
    protected $guarded = ['id'];
      
    public const STATUS_ACTIVE = 0;
    public const STATUS_ARCHIVED = 1;

    public const STATUSES = [
        "0" => ['label' =>'Active','color' => 'success'],
        "1" => ['label' =>'Archived','color' => 'danger'],
      
    ];
    public const BULK_ACTIVATION = 0;    
    public function getPrefix() {
        return "#F".str_replace('_1','','_'.(100000 +$this->id));
    }
       
    public function  createdBy(){
      return  $this->belongsTo(User::class,'created_by','id');
    }  
    protected function statusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn ($value) =>  (object)self::STATUSES[$this->status],
        );
    }
    public function categories()
    {
        return $this->belongsTo(Category::class,'category','id');
    }
    

}

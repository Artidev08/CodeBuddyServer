<?php
/**
 * Class Encounter
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
 

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;


class Encounter extends Model implements HasMedia {
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;    use InteractsWithMedia;
    protected $guarded = ['id'];
                     
    protected $casts = [
        'payload' => 'array',
        ];  
    public const BULK_ACTIVATION = 0;    
    public function getPrefix() {
        return "#E".str_replace('_1','','_'.(100000 +$this->id));
    }
     
    public function  folder(){
      return  $this->belongsTo(Folder::class,'folder_id','id');
    }     
    public function  createdBy(){
      return  $this->belongsTo(User::class,'created_by','id');
    }  


}

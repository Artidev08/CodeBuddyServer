<?php
/**
 * Class SampleDocument
 *
 * @category  zStarter
 *
 * @ref  zCURD
 * @author    Defenzelite <hq@defenzelite.com>
 * @license  https://www.defenzelite.com Defenzelite Private Limited
 * @version  <zStarter: 1.1.0>
 * @link        https://www.defenzelite.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    public function getPrefix()
    {
        return "#PR".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    protected $casts = [
        'keywords' => 'array'
    ];
    public function excludes(){
        return $this->hasMany(Exclude::class,'project_id','id');
    }
    public function combinationCodes(){
        return $this->hasMany(CombinationCode::class,'project_id','id');
    }
    public function lateralities(){
        return $this->hasMany(Laterality::class,'project_id','id');
    }
    public function gender(){
        return $this->hasMany(Gender::class,'project_id','id');
    }
    public function cricitalDiagnosis(){
        return $this->hasMany(CriticalDiagnosis::class,'project_id','id');
    }
    public function moreSpecific(){
        return $this->hasMany(MoreSpecific::class,'project_id','id');
    }
    public function projectEntry(){
        return $this->hasMany(ProjectEntry::class,'project_id','id');
    }
    public function charts(){
        return $this->hasMany(Chart::class,'project_id','id');
    }
}

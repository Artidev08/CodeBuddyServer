<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProceededContent extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function getPrefix() {
        return "#PC".str_replace('_1','','_'.(100000 +$this->id));
    }
     
    public function  folder(){
      return  $this->belongsTo(Folder::class,'folder_id','id');
    }     
    public function  createdBy(){
      return  $this->belongsTo(User::class,'user_id','id');
    }  
}

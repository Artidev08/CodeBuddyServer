<?php
/**
 * Class Cart
 *
 * @category ZStarter
 *
 * @ref     zCURD
 * @author  Defenzelite <hq@defenzelite.com>
 * @license https://www.defenzelite.com Defenzelite Private Limited
 * @version <zStarter: 1.1.0>
 * @link    https://www.defenzelite.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    protected $guarded = ['id'];
    
                       
    public function getPrefix()
    {
        return "#C".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function item()
    {
        return  $this->belongsTo(Item::class, 'type_id', 'id');
    }
    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
}

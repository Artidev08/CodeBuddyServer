<?php
/**
 * Class MyWishlist
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

class MyWishlist extends Model
{
    use HasFactory,HasFormattedTimestamps;
        
    protected $guarded = ['id'];
                       
    protected $casts = [
        'meta' => 'array',
    ];
    public function getPrefix()
    {
        return "#MW".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function type()
    {
        return  $this->belongsTo(User::class, 'type_id', 'id');
    }
}

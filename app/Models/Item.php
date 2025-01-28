<?php
/**
 * Class Item
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
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Item extends Model implements HasMedia
{
    use HasFactory,HasFormattedTimestamps;
    use InteractsWithMedia;

    protected $guarded = ['id'];
                                         
    protected $casts = [
        'meta' => 'array',
        ];
    public function getPrefix()
    {
        return "#I".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function category()
    {
        return  $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    
    public function orderItems()
    {
        return  $this->hasMany(OrderItem::class, 'item_id', 'id');
    }
}

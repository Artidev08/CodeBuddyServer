<?php
/**
 * Class Subscription
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

class Subscription extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    protected $guarded = ['id'];
                 
    public function getPrefix()
    {
        return "#S".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function UserSubscription()
    {
        return $this->hasManyThrough(UserSubscription::class, Type::class);
    }
}

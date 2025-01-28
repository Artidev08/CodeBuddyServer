<?php
/**
 * Class UserSubscription
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

class UserSubscription extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    protected $guarded = ['id'];
                   
    public function getPrefix()
    {
        return "#US".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function subscription()
    {
        return  $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }
    public function parent()
    {

        return  $this->belongsTo(User::class, 'parent_id', 'id');
    }
}

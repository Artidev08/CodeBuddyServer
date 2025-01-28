<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202306-V1.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;
use DateTimeZone;

class WishlistController extends Controller
{
  
    public function destroy(Wishlist $wishlist)
    {
        try {
            if ($wishlist) {
                $wishlist->delete();
                return back()->with('success', 'Wishlist deleted successfully');
            } else {
                return back()->with('error', 'Wishlist not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}

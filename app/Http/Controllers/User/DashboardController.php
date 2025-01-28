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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $readNotifications = Notification::where('user_id', auth()->id())->where('is_read', 1)->get();
        $unreadNotifications = Notification::where('user_id', auth()->id())->where('is_read', 0)->get();
        return view('user.dashboard.index', compact('readNotifications', 'unreadNotifications'));
    }
    public function logoutAs()
    {
        // If for some reason route is getting hit without someone already logged in
        if (!auth()->user()) {
            return redirect()->url('/');
        }
        
        // If admin id is set, relogin
        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {
            // Save admin id

            if (authRole() == "User") {
                $role = "?role=User";
            } else {
                $role = "?role=Admin";
            }
            $admin_id = session()->get('admin_user_id');

            session()->forget('admin_user_id');
            session()->forget('admin_user_name');
            session()->forget('temp_user_id');

            // Re-login admin
            auth()->loginUsingId((int) $admin_id);

            // Redirect to backend user page
            return redirect(route('admin.users.index').$role);
        } else {
            // return 'f';
            session()->forget('admin_user_id');
            session()->forget('admin_user_name');
            session()->forget('temp_user_id');

            // Otherwise logout and redirect to login
            auth()->logout();

            return redirect('/');
        }
    }
}

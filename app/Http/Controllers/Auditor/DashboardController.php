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

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Chart;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $readNotifications = Notification::where('user_id',$user->id)->where('is_read', 1)->get();
        $unreadNotifications = Notification::where('user_id',$user->id)->where('is_read', 0)->get();
        $maxChartPerDay = User::CHART_LIMITS[$user->chart_limit]['max'];
        $minDxPerDay = User::DX_LIMITS[$user->dx_limit]['min'];
        $maxDxPerDay = User::DX_LIMITS[$user->dx_limit]['max'];
        $todayAuditedCharts = Chart::where('auditor_id',auth()->id())->where('status',Chart::STATUS_AUDITED)->whereDate('updated_at',now())->count();
        if($todayAuditedCharts < $maxChartPerDay){
            $chart = Chart::withCount('entries')
            ->having('entries_count', '>=', $minDxPerDay)->having('entries_count', '<=', $maxDxPerDay)->where('auditor_id',auth()->id())->whereIn('status', [Chart::STATUS_COMPLETED])->where('is_audit_needed',1)->first();
            if(empty($chart))
            $chart = Chart::withCount('entries')
            ->having('entries_count', '>=', $minDxPerDay)->having('entries_count', '<=', $maxDxPerDay)->whereIn('status', [Chart::STATUS_COMPLETED])->where('is_audit_needed',1)->where('auditor_id', null)->first();
        }

        return view('auditor.dashboard.index', compact('readNotifications', 'unreadNotifications','chart'));
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
            $role = "?role=".AuthRole();
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

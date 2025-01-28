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

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Folder;
use App\Models\Role;
use App\Models\Encounter;
use App\Models\MailSmsTemplate;
use App\Models\ProceededContent;
use App\Models\Chart;
use App\Models\ChartChunk;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{

    public $label;

    function __construct()
    {
        $this->label = 'Dashboard';
    }
    public function index()
    {
        $user = auth()->user();
        $label = $this->label;
        $stats['memberCount']  = User::whereRoleIs(['member'])->count();
        $stats['EntryCount']  = User::whereRoleIs(['entry'])->count();
        $stats['AuditorCount']  = User::whereRoleIs(['auditor'])->count();
        $stats['folderCount']  = Folder::count();
        $stats['encounterCount']  = Encounter::count();
        $stats['commentCount']  = MailSmsTemplate::count();
        $stats['proceededContentCount']  = ProceededContent::count();
        $chartIds  = Chart::whereHas('chunks')->pluck('id')->toArray();
        $stats['inProgressCharts'] = ChartChunk::whereIn('chart_id', $chartIds)
                ->where(function($query) {
                    $query->where('status', '!=', ChartChunk::STATUS_COMPLETED)
                        ->orWhere('entry_sync_status', '!=', ChartChunk::ENTRY_SYNC_STATUS_COMPLETED)
                        ->orWhere('mc_sync_status', '!=', ChartChunk::MC_SYNC_STATUS_COMPLETED)
                        ->orWhere('hcc_sync_status', '!=', ChartChunk::HCC_SYNC_STATUS_COMPLETED)
                        ->orWhere('native_sync_status', '!=', ChartChunk::NATIVE_SYNC_STATUS_COMPLETED)
                        ->orWhere('location_sync_status', '!=', ChartChunk::LOCATION_SYNC_STATUS_COMPLETED)
                        ->orWhere('rt_sync_status', '!=', ChartChunk::RT_SYNC_STATUS_COMPLETED);
                })
                ->distinct('chart_id')
                ->count('chart_id');
    
        $stats['chartCount']  = Chart::count();
        return view('admin.dashboard.index',compact('user','label','stats'));
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


    public function cronDiagnosis(){
        return view('admin.cron.system-diagnosis');
    }

}

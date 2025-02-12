<?php
/**
 *
 * @category Hq.ai
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <Hq.ai: 202309-V1.2>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodeRegister;
use App\Models\Agent;
use App\Models\Project;

class DashboardController extends Controller
{

    public $label;

    function __construct()
    {
        $this->label = 'Dashboard';
    }

    /**start-hqai-m-index**/
    public function index()
    {
        $stats['label'] = $this->label;
        $latestMonth = now();
        $stats['agentsCount']  = Agent::count();
        $stats['codeRegisterCount']  = CodeRegister::count();
        $stats['projectCount']  = Project::count();

        return view('panel.admin.dashboard.index', $stats);
    }
     /**end-hqai-m-index**/



      /**start-hqai-m-logoutAs**/
    public function logoutAs()
    {
        // If for some reason route is getting hit without someone already logged in
        if (!auth()->user()) {
            return redirect()->url('/');
        }
        
        // If admin id is set, relogin
        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {
            // Save admin id

            if (auth()->user()->hasRole('user')) {
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
            return redirect(route('panel.admin.users.index').$role);
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
     /**end-hqai-m-logoutAs**/


     
    public function cronDiagnosis(){
        return view('panel.admin.cron.system-diagnosis');
    }
}

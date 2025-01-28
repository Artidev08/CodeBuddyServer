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

namespace App\Http\Controllers\Member;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Lead;
use App\Models\Folder;
use App\Models\Order;
use App\Models\Role;
use App\Http\Requests\UserRequest;
use App\Models\MailSmsTemplate;

class DashboardController extends Controller
{

    public $label;

    function __construct()
    {
        $this->label = 'Dashboard';
    }
    public function index(Request $request)
    {
        
        // return "s";
        $activeTab = request()->status;
        if(!request()->status){
            $activeTab = 0;
        }
        
        $folders = Folder::query();
        $folders->where('created_by',auth()->id());
        if($request->status != 'all'){
            $folders->where('status',$activeTab);
        }
        if($request->get('search')){
           $folders->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('title', 'like', '%' . $request->search . '%')
                    ->orWhere('created_by', 'like', '%' . $request->search . '%');
            });
        }
       
        $folders = $folders->latest()->paginate(60);
        $newUser = getNewAcquisitionForUsers();
        $newOrder = getNewAcquisitionForOrders();
        $user = auth()->user();
        $label = $this->label;
        $readNotifications = Notification::where('user_id',auth()->id())->where('is_read',1)->get();
        $unreadNotifications = Notification::where('user_id',auth()->id())->where('is_read',0)->get();
        $stats['adminsCount']  = User::whereRoleIs(['Admin'])->count();
        $stats['customersCount']  = User::whereRoleIs(['customers'])->count();
        $stats['leadConversationsCount']  = Conversation::where('type',Lead::class)->groupBy('type_id')->count();
        $stats['leadsCount']  = Lead::where('lead_type_id',5)->count();
        $orders  = Order::where('payment_status','!=',1)->get();
        $categories = getCategoriesByCode('FolderCategory');
        $adminIds = User::whereRoleIs('admin')->pluck('id')->toArray();
        $prompts = MailSmsTemplate::whereIn('user_id',$adminIds)->orWhere('user_id',auth()->id())->get();

        $memberPromptIds = Folder::where('created_by',auth()->id())->pluck('prompt_id')->toArray();
        $prompts = MailSmsTemplate::whereNotIn('id', $memberPromptIds)->get();
        foreach ($prompts as $prompt){
            $folder = New Folder;
            $folder->created_by = auth()->id();
            $folder->title = $prompt->title;
            $folder->prompt_id = $prompt->id;
            $folder->category = 18;
            $folder->save();
        }

        if ($request->ajax()) {
            return view('member.dashboard.load', ['folders'=>$folders])->render();  
        }
        return view('member.dashboard.index',compact('readNotifications','unreadNotifications','user','label','orders','stats','newUser','newOrder','folders','categories','activeTab','prompts'));
    }
    public function createModule()
    {
        $roles =Role::whereNotIn('id', [1])->pluck('display_name');
        return view('admin.module.create', compact('roles'));
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

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
use App\Http\Controllers\Admin\UserNoteController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\UserAddressController;
use App\Http\Controllers\Admin\PayoutDetailController;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Country;
use App\Models\UserKyc;
use App\Models\MailSmsTemplate;
// use App\Models\Payout;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{

    public $label;
    protected $userNoteController;
    protected $contactController;
    protected $userAddressController;
    protected $payoutDetailController;
    function __construct()
    {
        $this->userNoteController = new UserNoteController();
        $this->contactController = new ContactController();
        $this->userAddressController = new UserAddressController();
        $this->payoutDetailController = new PayoutDetailController();
        $this->label = request()->get('role') ?? 'User';
    }

    /**start-hqai-m-index**/
    public function index(Request $request)
    {

        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        // if()
        $roles = Role::whereIn('id', [3,2])->get()->pluck('name', 'id');
        $users = User::query();
        $users->whereRoleIsNot(['super_admin'])->where('id', '!=', auth()->id());
        if ($request->get('role')) {
            $users->whereRoleIs([request()->get('role')]);
        }
        if ($request->has('status') && $request->get('status') != null) {
            $users->whereStatus([request()->get('status')]);
        }
        if ($request->get('search')) {
            $users->where(
                function ($q) use ($request) {
                    $q-> where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%'.trim($request->search).'%')
                        ->orWhere('email', 'like', '%'.$request->get('search').'%')
                        ->orWhere('phone', 'like', '%'.$request->get('search').'%');
                }
            );
        }
        $statuses = User::STATUSES;
        $bulk_activation = User::BULK_ACTIVATION;
        $users= $users->latest()->paginate($length);
        $label = $this->label;
        if ($request->ajax()) {
            return view('panel.admin.users.load', ['users' => $users,'bulk_activation' => $bulk_activation])->render();
        }
        return view('panel.admin.users.index', compact('roles', 'users', 'label', 'statuses', 'bulk_activation'));
    }
    /**end-hqai-m-index**/

   
    public function print(Request $request)
    {
        $length = @$request->limit ?? 5000;
        $print_mode = true;
        $bulk_activation = User::BULK_ACTIVATION;
        $users_arr = collect($request->records['data'])->pluck('id');
        $users = User::whereIn('id', $users_arr)->latest()->paginate($length);
        return view('panel.admin.users.print', compact('users', 'bulk_activation', 'print_mode'))->render();
    }

    public function create()
    {
        try {
            $statuses = User::STATUSES;
            $roles = Role::get();
            $label = Str::singular($this->label);
            return view('panel.admin.users.create', compact('roles', 'statuses', 'label'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**start-hqai-m-store**/
    public function store(UserRequest $request)
    {
        // try {
        if (!$request->has('status')) {
            $request['status'] = 0;
        }
            // return $request->all();
            // Checking same role email user to prevent duplicate
        //     $user = User::whereRoleIs(strtolower($request->role))
        //    ->where('email', $request->email)->first();
            $user = User::where('email', $request->email)->first();
        if ($user) {
            return $request->wantsJson() ? response()->json(['error'=>'Email or phone number has already been taken.'], 500) : back()->with('error', 'Email or phone number has already been taken.')->withInput();
        }
            $wallet = 0;
        if ($request->role == 'Member') {
            $wallet = 20;
        }
            $user = User::create(
                [
                    'first_name'     => $request->first_name,
                    'last_name'     => $request->last_name,
                    'email'    => $request->email,
                    'status'    => $request->status,
                    'gender'    => $request->gender,
                    'phone'    => $request->phone,
                    'wallet'    => $wallet, // Opening with zero balance
                    'password' => Hash::make($request->password),
                ]
            );
                // assign new role to the user
            $user->syncRoles([$request->role]);
            $role = $user->roles[0]->display_name ?? '';
        if (request()->ajax()) {
            return response()->json(
                [
                    'role'=>$role,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record Created Successfully'
                ]
            );
        }
        // } catch (\Exception $e) {
        //     $bug = $e->getMessage();
        //     if (request()->ajax()) {
        //         return  response()->json([$bug]);
        //     } else {
        //         return redirect()->back()->with('error', $bug)->withInput($request->all());
        //     }
        // }
    }
    /**end-hqai-m-store**/


    /**start-hqai-m-loginAs**/
    public function loginAs($id)
    {
        try {
            if ($id == auth()->id()) {
                return back()->with('error', 'Do not try to login as yourself.');
            } else {
                $user   = User::find($id);
                session(['admin_user_id' => auth()->id()]);
                session(['admin_user_name' => auth()->user()->full_name]);
                session(['temp_user_id' => $user->id]);
                auth()->logout();
                
                // Login.
                auth()->loginUsingId($user->id);
    
                // Redirect.
                if (auth()->user()->hasRole('User')) {
                    return redirect(route('panel.user.dashboard.index'));
                } elseif (AuthRole() == 'Member') {
                    return redirect(route('panel.member.dashboard.index'));
                } else {
                    return redirect(route('panel.admin.dashboard.index'));
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    /**end-hqai-m-loginAs**/

    public function updateStatus($id, $s)
    {
        try {
            $user   = User::find($id);
            $user->update(['status' => $s]);
            $role = $user->roles[0]->display_name ?? '';
            if (request()->ajax()) {
                $message = array('status' => "success", 'message' => 'Success', 'title' => 'User status Updated');
                return response()->json($message);
            } else {
                return redirect()->route('panel.admin.users.index', '?role='.$role)->with('success', 'User status Updated!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }


    /**start-hqai-m-show**/
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = decrypt($id);
        }
        $user = User::whereId($id)->firstOrFail();
        $request['user_id'] = $id;
        $request['fetch_data'] = true;
        if ($request->ajax()) {
            switch ($request->active) {
                case 'lead-tab':
                    $notes = $this->userNoteController->index($request);
                    return $notes;
                break;
                case 'contact-tab':
                    $contacts = $this->contactController->index($request);
                    return $contacts;
                break;
                case 'address-tab':
                    $addresses = $this->userAddressController->index($request);
                    return $addresses;
                break;
                case 'Bank-details-tab':
                    $payoutDetails= $this->payoutDetailController->index($request);
                    return $payoutDetails;
                break;
            }
        }
        $countries = Country::get();
        $categories = getCategoriesByCode('LeadCategories');
        $jobTitleCategories = getCategoriesByCode('JobTitleCategories');
        $statuses = User::STATUSES;
        $roles = Role::where('id', '!=', 1)->pluck('display_name', 'id');
        $user_kyc = UserKyc::whereUserId($user->id)->first();
        $notes = $this->userNoteController->index($request);
        $addresses = $this->userAddressController->index($request);
        $payoutDetails = $this->payoutDetailController->index($request);
        $contacts = $this->contactController->index($request);
        return view('panel.admin.users.show', compact('user', 'user_kyc', 'statuses', 'roles', 'countries', 'categories', 'jobTitleCategories', 'notes', 'addresses', 'payoutDetails', 'contacts'));
    }
    /**end-hqai-m-show**/

    /**start-hqai-m-edit**/
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $user = User::whereId($id)->firstOrFail();
            $statuses = User::STATUSES;
            $user  = User::with('roles', 'permissions')->find($user->id);
            $user_kyc = UserKyc::whereUserId($user->id)->first();
            if ($user) {
                $user_role = $user->roles->first();
                $roles = Role::pluck('display_name', 'id');
                $label = Str::singular($this->label);
                return view('panel.admin.users.edit', compact('user', 'user_kyc', 'user_role', 'roles', 'statuses', 'label'));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
    /**end-hqai-m-edit**/

    /**start-hqai-m-update**/
    public function update(UserRequest $request, User $user)
    {
        try {
            $user = User::whereId($user->id)->first();
            // if (!$request->has('status')) {
            //     $request->status = 0;
            // }
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->email=$request->email;
            $user->dob=$request->dob;
            $user->gender=$request->gender;
            $user->phone=$request->phone;
            $user->permissions = $request->permissions;
            $user->is_verified=$request->is_verified;
            // $user->status=$request->status;
            if ($user->email_verified_at == null && $request->email_verify == 1) {
                $user->email_verified_at = now();
            } elseif ($user->email_verified_at != null && !$request->has('email_verify')) {
                $user->email_verified_at = null;
            }
            $user->save();

            $user->syncRoles([$request->role]);
            $role = $user->roles[0]->display_name ?? '';
            if (request()->ajax()) {
                return response()->json(
                    [
                        'role'=>$role,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Updated Successfully'
                    ]
                );
            }
            return redirect()->route('panel.admin.users.index', '?role='.$role)->with('success', 'User information updated successfully!');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['error'=>$bug], 500);
            } else {
                return redirect()->back()->with('error', $bug);
            }
        }
    }
    /**end-hqai-m-update**/

    public function bulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                        'status'=>'error',
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    User::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                case ('columnUpdate'):
                    User::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
    
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('status'):
                            $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                            $html['badge_label'] = $request->value != 0 ? "Active" : "Inactive";
    
                            $title = "Updated ".count($request->ids)." records successfully!";
                            break;
                        default:
                            $type = "error";
                            $title = 'No action selected!';
                    }
                    
                    break;
                default:
                    $type = "error";
                    $title = 'No action selected!';
            }
            
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'column'=>$request->column,
                        'action'=>$request->action,
                        'data' => $request->ids,
                        'title' => $title,
                        'html' => $html,
    
                    ]
                );
            }
        
            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }

    public function destroy(User $user, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $user = User::whereId($id)->firstOrFail();
        if ($user) {
            // Orders & Order Items Check
            $user_orders = $user->orders;
            if ($user_orders && $user_orders->count() > 0) {
                foreach ($user_orders as $key => $user_order) {
                    // Delete user_order
                    app('App\Http\Controllers\Admin\OrderController')->destroy($user_order);
                }
            }

            // Items
            $user_items = $user->items;
            if ($user_items && $user_items->count() > 0) {
                foreach ($user_items as $key => $user_item) {
                    // Delete user_item
                    app('App\Http\Controllers\Admin\ItemController')->destroy($user_item);
                }
            }

            // Payout Check

            $payouts = $user->payouts;
            if ($payouts && $payouts->count() > 0) {
                foreach ($payouts as $key => $payout) {
                    // Delete payout
                    app('App\Http\Controllers\Admin\PayoutController')->destroy($payout);
                }
            }

            $payoutDetails = $user->payoutDetails;
            if ($payoutDetails && $payoutDetails->count() > 0) {
                foreach ($payoutDetails as $key => $payoutDetail) {
                    // Delete payoutDetail
                    app('App\Http\Controllers\Admin\PayoutDetailController')->destroy($payoutDetail);
                }
            }
            
            // Wallet Logs Check
            $walletLogs = $user->walletLogs;
            if ($walletLogs && $walletLogs->count() > 0) {
                foreach ($walletLogs as $key => $walletLog) {
                    // Delete walletLog
                    app('App\Http\Controllers\Admin\WalletLogController')->destroy($walletLog);
                }
            }
            
            // Support Tickets Check
            $supportTickets = $user->supportTickets;
            if ($supportTickets && $supportTickets->count() > 0) {
                foreach ($supportTickets as $key => $supportTicket) {
                    // Delete supportTicket
                    if ($supportTicket) {
                        app('App\Http\Controllers\Admin\SupportTicketController')->destroy($supportTicket->id);
                    }
                }
            }
            
            // Role
            \DB::table('role_user')->where('user_id', $user->id)->delete();
            
            // Permissions
            \DB::table('permission_user')->where('user_id', $user->id)->delete();
            
            // Blogs
            $blogs = $user->blogs;
            if ($blogs && $blogs->count() > 0) {
                foreach ($blogs as $key => $blog) {
                    // Delete blog
                    app('App\Http\Controllers\Admin\BlogController')->destroy($blog);
                }
            }
            
            // Subscriber
            $userSubscriptions = $user->userSubscriptions;
            if ($userSubscriptions && $userSubscriptions->count() > 0) {
                foreach ($userSubscriptions as $key => $userSubscription) {
                    // Delete userSubscriptions
                    if ($userSubscription) {
                        app('App\Http\Controllers\Admin\UserSubscriptionController')->destroy($userSubscription->id);
                    }
                }
            }
            
            // wishlists
            $wishlists = $user->wishlists;
            if ($wishlists && $wishlists->count() > 0) {
                foreach ($wishlists as $key => $wishlist) {
                    // Delete wishlist
                    app('App\Http\Controllers\Admin\WishlistController')->destroy($wishlist);
                }
            }
            // Conversation
            $conversations = $user->conversations;
            if ($conversations && $conversations->count() > 0) {
                foreach ($conversations as $key => $conversation) {
                    // Delete Conversation
                    app('App\Http\Controllers\Admin\ConversationController')->delete($conversation);
                }
            }
            // leads
            $leads = $user->leads;
            if ($leads && $leads->count() > 0) {
                foreach ($leads as $key => $lead) {
                    // Delete lead
                    app('App\Http\Controllers\Admin\LeadController')->destroy($lead);
                }
            }
            $notifications = $user->notifications;
            if ($notifications->count() > 0) {
                foreach ($notifications as $key => $notification) {
                    // Delete notification
                    app('App\Http\Controllers\Admin\NotificationController')->destroy($notification);
                }
            }
            $payments = $user->payments;
            if ($payments && $payments->count() > 0) {
                foreach ($payments as $key => $payment) {
                    // Delete payment
                    app('App\Http\Controllers\Admin\PaymentController')->destroy($payment);
                }
            }
            //user Addresses check
            $addresses = $user->addresses;
            if ($addresses && $addresses->count() > 0) {
                foreach ($addresses as $key => $Address) {
                    // Delete Address
                    app('App\Http\Controllers\Admin\UserAddressController')->destroy($Address);
                }
            }
            //user kycs check
            $kycs = $user->kycs;
            if ($kycs && $kycs->count() > 0) {
                foreach ($kycs as $key => $kyc) {
                    // Delete kyc
                    app('App\Http\Controllers\Admin\UserKycController')->destroy($kyc);
                }
            }
            //user Logs check
            $logs = $user->logs;
            if ($logs && $logs->count() > 0) {
                foreach ($logs as $key => $log) {
                    // Delete log
                    app('App\Http\Controllers\Admin\UserLogController')->destroy($log);
                }
            }
            
            $user->delete();
            return back()->with('success', 'User removed!');
        } else {
            return back()->with('error', 'User not found');
        }
    }



    public function updateKycStatus(Request $request)
    {
            $user = UserKyc::whereUserId($request->user_id)->firstOrFail();
            $kyc_info = json_decode($user->details, true);

        if (is_null($kyc_info)) {
            abort(404);
        }
        $new_kyc_info = [
            'document_type' => $kyc_info['document_type'],
            'document_number' => $kyc_info['document_number'],
            'document_front' => $kyc_info['document_front'],
            'document_back' => $kyc_info['document_back'],
            'admin_remark' => $request['remark'],
        ];

        $new_kyc_info = json_encode($new_kyc_info);

        if ($request->status == 1) {
            $mailcontent_data = MailSmsTemplate::where('code', '=', "Verified-KYC")->first();
            if ($mailcontent_data) {
                $arr=[
                    '{id}'=> $user->id,
                    '{name}'=>NameById($user->id),
                ];
                $action_button = null;
                TemplateMail($user->name, $mailcontent_data, $user->email, $mailcontent_data->type, $arr, $mailcontent_data, $chk_data = null, $mail_footer = null, $action_button);
            }
            $onsite_notification = [
                'title' => "KYC accepted",
                'notification' => 'Your KYC has been verified successfully!',
                'link' => '#',
                'user_id' => $request->user_id,
            ];
            pushOnSiteNotification($onsite_notification);
        }
        
        if ($request->status == 2) {
            $mailcontent_data = MailSmsTemplate::where('code', '=', "Rejected-KYC")->first();
            if ($mailcontent_data) {
                $arr=[
                '{id}'=> $user->id,
                '{name}'=>NameById($user->id),
                ];
                $action_button = null;
                TemplateMail($user->name, $mailcontent_data, $user->email, $mailcontent_data->type, $arr, $mailcontent_data, $chk_data = null, $mail_footer = null, $action_button);
            }
            $onsite_notification['user_id'] =  $request->user_id;
            $onsite_notification['title'] = "Account Verification Request Rejected";
            $onsite_notification['link'] = route('panel.admin.profile.index')."?active=account";
            $onsite_notification['notification'] = "Your Account Verification has been rejected because of some reason please try again later.";
            pushOnSiteNotification($onsite_notification);
            $user_kyc = UserKyc::whereUserId($request->user_id);
            $user_kyc->delete();
        }
        if ($request->status == 0) {
            $user->update(
                [
                'status' => $request->status,
                ]
            );
        }
        
        $user->update(
            [
            'details' =>$new_kyc_info,
            'status' => $request->status,
            ]
        );

        return redirect()->back()->with('success', 'eKYC update successfully!');
    }

    
    public function getUsers(Request $request)
    {
        $input = $request->all();
        $users = User::query();
        $users->select(['id','first_name','last_name','email','phone']);
        if ($request->has('query') && !empty($input['query'])) {
            $users->whereRoleIs('User')
                ->where("first_name", "like", '%'.$input['query'].'%')
                ->orWhere("last_name", "like", '%'.$input['query'].'%')
                ->orWhere("email", "like", '%'.$input['query'].'%')
                ->orWhere("phone", "like", '%'.$input['query'].'%');
        } else {
            $users->whereRoleIs(['User']);
        }
        $users = $users->latest()->limit(15)->get();
        return response()->json($users);
    }
}

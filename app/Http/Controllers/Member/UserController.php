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

use App\Http\Controllers\Controller;
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

    function __construct()
    {
 
        $this->label = request()->get('role') ?? 'User';
    }
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
            return view('admin.users.load', ['users' => $users,'bulk_activation' => $bulk_activation])->render();
        }
        return view('admin.users.index', compact('roles', 'users', 'label', 'statuses','bulk_activation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {
        $users_arr = collect($request->records['data'])->pluck('id');
        $users = User::whereIn('id', $users_arr)->get();
        return view('admin.users.print', ['users' => $users])->render();
    }

    public function create()
    {
        try {
            $statuses = User::STATUSES;
            $roles = Role::get();
            $label = Str::singular($this->label);
            return view('admin.users.create', compact('roles', 'statuses', 'label'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
    public function store(UserRequest $request)
    {
        // return $request->all();
        try {
            if (!$request->has('status')) {
                $request['status'] = 0;
            }
            // store user information
            $user = User::create(
                [
                'first_name'     => $request->first_name,
                'last_name'     => $request->last_name,
                'email'    => $request->email,
                'status'    => $request->status,
                'gender'    => $request->gender,
                'phone'    => $request->phone,
                'wallet'    => 0, // Opening with zero balance
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
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            if (request()->ajax()) {
                return  response()->json([$bug]);
            } else {
                return redirect()->back()->with('error', $bug)->withInput($request->all());
            }
        }
    }


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
                if (AuthRole() == 'User') {
                    return redirect(route('user.dashboard.index'));
                } else {
                    return redirect(route('admin.dashboard.index'));
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

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
                return redirect()->route('admin.users.index', '?role='.$role)->with('success', 'User status Updated!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }



    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = decrypt($id);
        }
        $user = User::whereId($id)->firstOrFail();
        $countries = Country::get();
        $categories = getCategoriesByCode('LeadCategories');
        $jobTitleCategories = getCategoriesByCode('JobTitleCategories');
        $statuses = User::STATUSES;
        $roles = Role::where('id', '!=', 1)->pluck('display_name', 'id');
        $user_kyc = UserKyc::whereUserId($user->id)->first();
        return view('admin.users.show', compact('user', 'user_kyc', 'statuses', 'roles', 'countries', 'categories', 'jobTitleCategories'));
    }


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
                return view('admin.users.edit', compact('user','user_kyc','user_role', 'roles', 'statuses'));
            } else {
                return redirect('404');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(UserRequest $request, User $user)
    {
        // return $request->all();
        if (Session::has('last_pay_attempt')) {
            $last_attempt = Session::get('last_pay_attempt');
            $difference = $last_attempt->diffInMinutes(now());
            $seconds = 120-$last_attempt->diffInSeconds(now());
            if ($difference < 2) {
                if ($request->ajax()) {
                    return response()->json(['error'=>"Hold on, Please try after $seconds seconds."], 400);
                } else {
                    return redirect()->back()->with('error', "Hold on, Please try after $seconds seconds.")->withInput($request->all());
                }
            }
        }
        Session::put('last_pay_attempt', now());
        $user = User::whereId($user->id)->first();
        if (!$request->has('status')) {
            $request->status = 0;
        }
        try {
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->email=$request->email;
            $user->dob=$request->dob;
            $user->gender=$request->gender;
            $user->phone=$request->phone;
            $user->is_verified=$request->is_verified;
            $user->status=$request->status;
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
            return redirect()->route('admin.users.index', '?role='.$role)->with('success', 'User information updated successfully!');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['error'=>$bug], 500);
            } else {
                return redirect()->back()->with('error', $bug);
            }
        }
    }
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

    public function destroy(User $user)
    {
       
        if ($user) {
            // Orders & Order Items Check
            $user_orders = $user->orders;
            if ($user_orders->count() > 0) {
                foreach ($user_orders as $key => $user_order) {
                    // Delete user_order
                    app('App\Http\Controllers\Admin\OrderController')->destroy($user_order);
                }
            }

            // Items
            $user_items = $user->items;
            if ($user_items->count() > 0) {
                foreach ($user_items as $key => $user_item) {
                    // Delete user_item
                    app('App\Http\Controllers\Admin\ItemController')->destroy($user_item);
                }
            }

            // Payout Check

            $payouts = $user->payouts;
            if ($payouts->count() > 0) {
                foreach ($payouts as $key => $payout) {
                    // Delete payout
                    app('App\Http\Controllers\Admin\PayoutController')->destroy($payout);
                }
            }

            $payoutDetails = $user->payoutDetails;
            if ($payoutDetails->count() > 0) {
                foreach ($payoutDetails as $key => $payoutDetail) {
                    // Delete payoutDetail
                    app('App\Http\Controllers\Admin\PayoutDetailController')->destroy($payoutDetail);
                }
            }
            
            // Wallet Logs Check
            $walletLogs = $user->walletLogs;
            if ($walletLogs->count() > 0) {
                foreach ($walletLogs as $key => $walletLog) {
                    // Delete walletLog
                    app('App\Http\Controllers\Admin\WalletLogController')->destroy($walletLog);
                }
            }
            
            // Support Tickets Check
            $supportTickets = $user->supportTickets;
            if ($supportTickets->count() > 0) {
                foreach ($supportTickets as $key => $supportTicket) {
                    // Delete supportTicket
                    app('App\Http\Controllers\Admin\SupportTicketController')->destroy($supportTicket);
                }
            }
            
            // Role
            \DB::table('role_user')->where('user_id', $user->id)->delete();
            
            // Permissions
            \DB::table('permission_user')->where('user_id', $user->id)->delete();
            
            // Blogs
            $blogs = $user->blogs;
            if ($blogs->count() > 0) {
                foreach ($blogs as $key => $blog) {
                    // Delete blog
                    app('App\Http\Controllers\Admin\BlogController')->destroy($blog);
                }
            }
            
            // Subscriber
            $userSubscriptions = $user->userSubscriptions;
            if ($userSubscriptions->count() > 0) {
                foreach ($userSubscriptions as $key => $userSubscription) {
                    // Delete userSubscriptions
                    app('App\Http\Controllers\Admin\UserSubscriptionController')->destroy($userSubscription);
                }
            }
            
            // wishlists
            $wishlists = $user->wishlists;
            if ($wishlists->count() > 0) {
                foreach ($wishlists as $key => $wishlist) {
                    // Delete wishlist
                    app('App\Http\Controllers\Admin\WishlistController')->destroy($wishlist);
                }
            }
            // Conversation
            $conversations = $user->conversations;
            if ($conversations->count() > 0) {
                foreach ($conversations as $key => $conversation) {
                    // Delete Conversation
                    app('App\Http\Controllers\Admin\ConversationController')->delete($conversation);
                }
            }
            // leads
            $leads = $user->leads;
            if ($leads->count() > 0) {
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
            if ($payments->count() > 0) {
                foreach ($payments as $key => $payment) {
                    // Delete payment
                    app('App\Http\Controllers\Admin\PaymentController')->destroy($payment);
                }
            }
            //user Addresses check
            $addresses = $user->addresses;
            if ($addresses->count() > 0) {
                foreach ($addresses as $key => $Address) {
                    // Delete Address
                    app('App\Http\Controllers\Admin\UserAddressController')->destroy($Address);
                }
            }
            //user kycs check
            $kycs = $user->kycs;
            if ($kycs->count() > 0) {
                foreach ($kycs as $key => $kyc) {
                    // Delete kyc
                    app('App\Http\Controllers\Admin\UserKycController')->destroy($kyc);
                }
            }
            //user Logs check
            $logs = $user->logs;
            if ($logs->count() > 0) {
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
            $onsite_notification['link'] = route('admin.profile.index')."?active=account";
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

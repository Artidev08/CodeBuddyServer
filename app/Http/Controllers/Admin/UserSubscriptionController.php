<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Crypt;

class UserSubscriptionController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $user_subscriptions = UserSubscription::query();
         
        if ($request->get('search')) {
            $user_subscriptions->whereHas('user',function($user) use ($request){
                $user->where(User::raw("CONCAT(first_name,' ',last_name)"),'like','%' . $request->get('search').'%');
            })->orWhere('id', 'like', '%'.$request->search.'%');
        }
            
        if ($request->get('from') && $request->get('to')) {
            $user_subscriptions->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }

        if ($request->get('user')) {
            $user_subscriptions->where('user_id',$request->get('user'));
        }
        if ($request->get('asc')) {
            $user_subscriptions->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $user_subscriptions->orderBy($request->get('desc'), 'desc');
        }
           $user_subscriptions = $user_subscriptions->latest()->paginate($length);
          

        if ($request->ajax()) {
            return view('admin.user-subscriptions.load', ['user_subscriptions' => $user_subscriptions])->render();
        }
 
           return view('admin.user-subscriptions.index', compact('user_subscriptions'));
    }

    
    public function print(Request $request)
    {
        $user_subscriptions_arr = collect($request->records['data'])->pluck('id');
        $user_subscriptions = UserSubscription::whereIn('id',$user_subscriptions_arr)->get();
        return view('admin.user-subscriptions.print', ['user_subscriptions' => $user_subscriptions])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.user-subscriptions.create');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate(
            $request,
            [
                        'user_id'     => 'required',
                        'subscription_id'     => 'required',
                        'from_date'     => 'required',
                        'to_date'     => 'required',
                        'parent_id'     => 'nullable',
            ]
        );
        
        try {
            $user_subscription = UserSubscription::create($request->all());
            
                                    
            return redirect()->route('admin.user-subscriptions.index')->with('success', 'User Subscription Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserSubscription $user_subscription)
    {
        try {
            return view('admin.user-subscriptions.show', compact('user_subscription'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserSubscription $user_subscription)
    {
        try {
            return view('admin.user-subscriptions.edit', compact('user_subscription'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserSubscription $user_subscription)
    {
        
        $this->validate(
            $request,
            [
                        'subscription_id'     => 'required',
                        'from_date'     => 'required',
                        'to_date'     => 'required',
                        'parent_id'     => 'sometimes',
            ]
        );
                
        try {
            if ($user_subscription) {
                $chk = $user_subscription->update($request->all());
                return redirect()->route('admin.user-subscriptions.index')->with('success', 'Record Updated!');
            }
            return back()->with('error', 'User Subscription not found')->withInput($request->all());
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserSubscription $user_subscription)
    {
        try {
            if ($user_subscription) {
                $user_subscription->delete();
                return back()->with('success', 'User Subscription deleted successfully');
            } else {
                return back()->with('error', 'User Subscription not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}

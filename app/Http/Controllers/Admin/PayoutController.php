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

use App\Models\Payout;
use App\Models\User;
use App\Models\WalletLog;
use App\Models\PayoutDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayoutRequest;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Payouts';
    }
    public function index(Request $request)
    {
        if (!$request->has('from') && !$request->has('to')) {
            $start_date = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
            return
            redirect(route('admin.payouts.index')."?from=$start_date&to=$end_date");
        }
         $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
         $payouts = Payout::query();
        if ($request->get('search')) {
            $payouts->where('id', 'like', '%'.$request->search.'%')
                ->orWhere('status', 'like', '%'.$request->search.'%')
                ->orWhere('amount', 'like', '%'.$request->search.'%')
                ->orWhereHas(
                    'user',
                    function ($q) use ($request) {
                            $q->where(\DB::raw('CONCAT(first_name, " ",last_name)'), 'LIKE', '%' .request()->get('search'). '%')
                                ->orWhere('first_name', 'like', '%'.request()->get('search').'%')
                                ->orWhere('last_name', 'like', '%'.request()->get('search').'%');
                    }
                );
        }
        if ($request->get('from') && $request->get('to')) {
            $payouts->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->get('asc')) {
            $payouts->orderBy($request->get('asc'), 'asc');
        }
        if ($request->has('status') && $request->get('status') != null) {
            $payouts->whereStatus($request->get('status'));
        }
        if ($request->has('user') && $request->get('user') != null) {
            $payouts->whereUserId($request->get('user'));
        }
        if ($request->get('desc')) {
            $payouts->orderBy($request->get('desc'), 'desc');
        }
            $payouts = $payouts->latest()->paginate($length);
            $statuses = Payout::STATUSES;
            $users = User::whereRoleIs('user')->get();
        if ($request->ajax()) {
            return view('admin.payouts.load', ['payouts' => $payouts])->render();
        }
            
        $label = $this->label;
        return view('admin.payouts.index', compact('payouts', 'statuses', 'users', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Payout::STATUSES;
        $users = User::whereRoleIs('user')->get();
        return view('admin.payouts.create', compact('statuses', 'users'));
    }
    
    public function print(Request $request)
    {
        $payouts_arr = collect($request->records['data'])->pluck('id');
        $payouts = Payout::whereIn('id', $payouts_arr)->get();
        return view('admin.payouts.print', ['payouts' => $payouts])->render();
    }
    /**
     * Store a newly created resource in $payoutstorage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayoutRequest $request)
    {
        // return $request->all();
        $this->validate(
            $request,
            [
            'user' => 'required',
            'category_id' => 'required',
            'amount'=> 'required|max:9',
            'status' => 'nullable',
            'address'=> 'nullable'
           
            ]
        );
        try {
            // return request->all();
            $payout = Payout::create($request->all());
            return redirect()->route('admin.payouts.index')->with('success', 'Payout Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try{
            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }
            $payout = Payout::whereId($id)->firstOrFail();
           
            $user_details = (object)$payout->bank_details;
            return view('admin.payouts.show', compact('payout', 'user_details'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function edit(Payout $payout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Payout       $payout
     * @return \Illuminate\Http\Response
     */
    public function status(PayoutRequest $request, Payout $payout)
    {
        try {
            // return $request->all();
            if ($request->status == 1) {
                $payout->update(
                    [
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'txn_no' => $request->txn_no,
                    'status' => $request->status,
                    'remark' => 'Admin accept your payout request.'
                    ]
                );
                $user = User::whereId($payout->user_id)->first();
                $after_balance = $user->wallet - $payout->amount;
                $user->update(
                    [
                    'wallet' =>  $after_balance
                    ]
                );

                //Wallet log Creation
                WalletLog::create(
                    [
                    'type' => 'debit',
                    'user_id' => $payout->user_id,
                    'revert_by' => auth()->id(),
                    'amount' => $payout->amount,
                    'status' => WalletLog::STATUS_ACCEPTED,
                    'after_balance' => $after_balance,
                    'remark' => "Payout Accepted PIQ#".$payout->id." with Txn no".$request->txn_no,
                    ]
                );

                $data['title'] = "Payout Request Accepted!";
                $data['notification'] = "Your payout request has been Approved by Admin.";
            } else {
                $payout->update(
                    [
                    'remark' => $request->remark,
                    'status' => $request->status,
                    ]
                );
                
                $data['title'] = "Payout Request Rejected!";
                $data['notification'] = "Your payout request has been Rejected by Admin for the reason ".$request->remark.'.';
            }
            // notification send
            $data['user_id'] =  $payout->user_id;
            $data['link'] = route('user.notification.index');
            pushOnSiteNotification($data);
            
            if($request->ajax())
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Transaction Updated Successfully!'
                    ]
                );
            return back()->with('success', 'Transaction Updated Successfully');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function update(PayoutRequest $request, Payout $payout)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payout $payout)
    {
        try {
            if ($payout) {
                $payout->delete();
                return back()->with('success', 'Payout deleted successfully');
            } else {
                return back()->with('error', 'Payout not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
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
                    Payout::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                case ('columnUpdate'):
                    Payout::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('status'):
                            if ($request->value == 0) {
                                $html['badge_color'] = "warning";
                                $html['badge_label'] = "In Review";
                            } elseif ($request->value == 1) {
                                $html['badge_color'] = "success";
                                $html['badge_label'] = "Paid";
                            } elseif ($request->value == 2) {
                                $html['badge_color'] = "danger";
                                $html['badge_label'] = "Rejected";
                            }
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
}

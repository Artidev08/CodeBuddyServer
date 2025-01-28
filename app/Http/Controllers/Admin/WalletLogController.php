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

use App\Http\Controllers\Controller;
use App\Models\WalletLog;
use App\Models\User;
use Illuminate\Http\Request;

class WalletLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $userName = User::where('id', $id)->first();
        $walletLogs = WalletLog::query();
        if ($request->get('search')) {
            $walletLogs->where('remark', 'like', '%'.$request->get('search').'%');
                
        }

        $statuses = WalletLog::STATUSES;
        if ($request->has('status') && $request->get('status') != null) {
            $walletLogs->whereStatus([request()->get('status')]);
        }
        if ($request->has('date')) {
            $walletLogs->whereDate('created_at', $request->date);
        }
        if ($request->get('from') && $request->get('to')) {
            $walletLogs->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
            $walletLogs = $walletLogs->whereUserId($id)->latest()->paginate($length);
        
        if($request->ajax())
        return view('admin.wallet-logs.load', compact('id', 'walletLogs', 'userName', 'statuses'));
        else
        return view('admin.wallet-logs.index', compact('id', 'walletLogs', 'userName', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function status(WalletLog $walletLog, $status)
    {
        // return 's';
        if ($walletLog) {
            $walletLog->status = $status;
            $walletLog->save();

            if ($status == 1) {
                $user =  User::find($walletLog->user_id);
                $user->wallet = $user->wallet + $walletLog->amount;
                $user->save();
            }
            if ($status == 1) {
                $data['user_id'] =  $walletLog->user_id;
                $data['title'] = "Payment Request Accepted";

                $data['link'] = route('user.wallet.index');
                $data['notification'] = 'Your Payment Request has been approved by admin!';
                pushOnSiteNotification($data);
            } else {
                $data['user_id'] =  $walletLog->user_id;
                $data['title'] = "Payment Request Rejected";
                $data['link'] = route('user.wallet.index');
                $data['notification'] = 'Your Payment Request has been rejected by admin!';
                pushOnSiteNotification($data);
            }

            return back()->with('success', 'Updated Record!');
        } else {
            return back()->with('error', 'Record not found!');
        }
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WalletLog $walletLog
     * @return \Illuminate\Http\Response
     */
    public function show(WalletLog $walletLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WalletLog $walletLog
     * @return \Illuminate\Http\Response
     */
    public function edit(WalletLog $walletLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\WalletLog    $walletLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletLog $walletLog)
    {
        $user_record = User::where('id', $request->user_id)->first();
        if ($request->type == 'debit') {
            if ($user_record->wallet  <  $request->amount) {
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'role'=>$request->role,
                            'status'=>'error',
                            'message' => 'Error',
                            'title' => 'Amount cannot exceed current balance!'
                        ]
                    );
                }
                return redirect()->back()->with('error', 'Amount cannot exceed current balance!');
            }
        }
        
        if ($request->type == 'credit') {
            $remark = 'Admin Added '.format_price($request->amount);
            $after_balance = $user_record->wallet + $request->amount;
        } else {
            $remark = 'Admin Subtracted '.format_price($request->amount);
            $after_balance = $user_record->wallet - $request->amount;
        }
        
        $wallet_record = WalletLog::create(
            [
            'user_id'=>$request->user_id,
            'revert_by'=>auth()->id(),
            'type'=>$request->type,
            'amount'=>$request->amount,
            'after_balance'=>$after_balance,
            'remark'=>$remark,
            'status'=>1,
            ]
        );

        if ($request->type == 'credit') {
            $user_record->increment('wallet', $request->amount);
        } else {
            $user_record->decrement('wallet', $request->amount);
        }

        if (request()->ajax()) {
            return response()->json(
                [
                    'role'=>$request->role,
                    'user_id'=>$request->user_id,
                    'wallet_balance'=>$user_record->wallet,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Wallet Balance Updated Successfully'
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Wallet Balance Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WalletLog $walletLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletLog $walletLog)
    {
        try {
            if ($walletLog) {
                $walletLog->delete();
                return back()->with('success', 'Wallet Log deleted successfully');
            } else {
                return back()->with('error', 'Wallet Log not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}

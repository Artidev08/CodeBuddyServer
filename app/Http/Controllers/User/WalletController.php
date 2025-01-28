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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WalletRequest;
use App\Models\WalletLog;
use App\Models\Payout;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $walletLogs = WalletLog::whereUserId(auth()->id())-> latest()->simplePaginate(5);
        return view('user.wallet.index', compact('walletLogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function store(WalletRequest $request)
    {
        $balance = auth()->user()->wallet_balance;
        $walletLog = new WalletLog();
        $walletLog->amount = $request->amount;
        $walletLog->user_id = auth()->id();
        $walletLog->type = $request->type;
        $walletLog->status = 0;
        $walletLog->remark = auth()->user()->full_name.' have request for amount '.format_price($request->amount);
        $walletLog->save();

        $admin_notification = [
            'title' => "Payment Request",
            'notification' => auth()->user()->full_name." has requested an amount of ".format_price($request->amount).'.',
            'link' => route('admin.wallet-logs.index', $walletLog->id),
            'user_id' => 1,
        ];
        pushOnSiteNotification($admin_notification);

        return back()->with('success', 'We have created a recharge request for you, once it is approved you will be notified!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // try {
        //     if($walletLog){
        //         $walletLog->update(['wallet_status' => $request->wallet_status, "updated_by" => auth()->id()]);
        //         // Notification Update Payment Status
        //         return $walletLog;
        //         $data['user_id'] =$walletLog->user_id;
        //         $data['title'] = "Your recharge Status has been updated by admin";
        //         $data['link'] = route('admin.wallet-logs.index');
        //         $data['notification'] = 'Your recharge has been '.$walletLog->status->label.' by admin!';
        //             pushOnSiteNotification($data);
        //         return back()->with('success', 'Status updated Successfully');
        //     }
        //     return back()->with('error', 'Story Not Found');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'There was an error: ' . $e->getMessage());
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

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

use App\Http\Controllers\Controller;
use App\Http\Requests\PayoutRequest;
use App\Models\Payout;
use App\Models\PayoutDetail;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\UserKyc;
use App\Models\Country;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payouts = Payout::whereUserId(auth()->id())->latest()->simplePaginate(5);
        $is_pending_request = Payout::whereUserId(auth()->id())->whereStatus(Payout::STATUS_IN_REVIEW)->first();
        return view('user.payout.index', compact('payouts', 'is_pending_request'));
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
    public function store(PayoutRequest $request)
    {
        // return $request->all();
        $balance = auth()->user()->wallet;
        $payout = new Payout();
        if (auth()->user()->wallet < $request->amount) {
            return back()->with('error', 'Requested Amount should be less than Wallet Balance!');
        } else {
            $payout->amount = $request->amount;
        }
        $payoutDetails = PayoutDetail::whereUserId(auth()->id())->whereJsonContains('payload->bank_name', $request->bank_name)->first();
        if (empty($payoutDetails)) {
            return back()->with('error', 'Bank Ac not found!!');
        }
        
        $payout->bank_details = $payoutDetails->payload;
        $payout->user_id = auth()->id();
        $payout->status = 0;
        $payout->type = $request->type;

        $payout->save();
        $data['user_id'] =  1;
        $data['title'] = "Payout Request!";
        $data['link'] = route('user.wallet.index');
        $data['notification'] = auth()->user()->full_name.' has requested an payout amount of '.format_price($payout->amount).'.';
        pushOnSiteNotification($data);
        return back()->with('success', 'We have created a payout request for you, once it is approved you will be notified!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function show(Payout $payout)
    {
        //
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
    public function update(Request $request, Payout $payout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payout $payout)
    {
        //
    }
}

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
use App\Models\PayoutDetail;
use Illuminate\Http\Request;

class PayoutDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $payoutDetail = PayoutDetail::whereUserId(auth()->id())->get();
        return view('user.setting.index', compact('payoutDetails'));
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
        // return $request->all();
        $request->validate([
            'account_no' => ['required','max:16'],
        ]);

        try {
            $user_id = auth()->user()->id;
            $payload = [
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'branch' => $request->branch,
                'hereBy' => $request->hereBy,
            ];
            $payoutDetail = new PayoutDetail();
            $payoutDetail->user_id = $user_id;
            $payoutDetail->type = $request->type;
            $payoutDetail->payload = $payload;
            $payoutDetail->save();
            return back()->with('success', 'Your Payout Details Created Successfully!');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function show(PayoutDetail $payoutDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function edit(PayoutDetail $payoutDetail)
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
    public function update(Request $request)
    {
        // return $request->all();
        $request->validate([
            'id'  => ['required'],
            'account_no' => ['required','max:16'],
        ]);
        
        // $this->validate(
        //     $request,
        //     [
        //     'id'     => 'required',
        //     'account_no'     => 'required |min:0|max:16',

        //     ]
        // );
         $payoutDetail = PayoutDetail::where('id', $request->id)->first();
        if ($payoutDetail) {
            $payload = [
                'account_holder_name' => $request->account_holder_name,
                'account_no' => $request->account_no,
                'ifsc_code' => $request->ifsc_code,
                'bank_name' => $request->bank_name,
                'branch' => $request->branch,
                'hereBy' => $request->hereBy,
            ];
            $request['user_id'] = auth()->id();
            $request['payload'] = $payload;
            $payoutDetail->payload = $payload;
            $payoutDetail->save();

            return back()->with('success', ' Information Updated!');
        }
        return back()->with('error', ' Payout not found!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payout $payout
     * @return \Illuminate\Http\Response
     */
    public function destroy(PayoutDetail $payoutDetail)
    {
    
        try {
            if ($payoutDetail) {
                $payoutDetail->delete();
                return back()->with('success', 'Page Deleted Successfully!');
            } else {
                return back()->with('error', 'Page not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}

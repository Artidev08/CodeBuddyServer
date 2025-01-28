<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payout;
use App\Models\PayoutDetail;
use App\Models\User;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $payouts = Payout::where('user_id', auth()->id())->with(
                ['user'=>function ($q) {
                    $q->select('id', 'first_name', 'last_name', 'avatar', 'email', 'phone', 'gender', 'country_id', 'state_id', 'city_id')->with('payoutDetails');
                }]
            )->latest()->get();
            foreach ($payouts as $key => $payout) {
                $payout->bank_details = json_decode($payout->bank_details);
                $payout->user_details = json_decode($payout->user_details);
            }
            if ($payouts) {
                return $this->success($payouts);
            } else {
                return $this->success([]);
            }
        } catch (\Exception | \Error $e) {
            return $this->errorOk('Something went wrong!');
        }
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
    public function store(Request $request)
    {
        $request->validate(
            [
            'type' => ['required'],
            'amount' => ['required'],
            ]
        );

        if ($request->get('type') == "Bank") {
            $chk = PayoutDetail::whereUserId(auth()->id())->where('type', 'Bank')->first();
            if (!$chk) {
                return $this->errorOk('No Bank Associated Record Found'. $e->getMessage());
            }
        }

        if ($request->get('type') == "Upi") {
            $chk = PayoutDetail::whereUserId(auth()->id())->where('type', 'Upi')->first();
            if (!$chk) {
                return $this->errorOk('No UPI Associated Record Found'. $e->getMessage());
            }
        }

        try {
            // Check User Wallet Balance
            if (auth()->user()->wallet < $request->get('amount')) {
                return $this->errorOk('Sorry, Your wallet balance is lower than your requested amount.');
            }

            $bank_details = $chk->payload;
            $user_details = User::whereId(auth()->id())->select(['first_name','last_name','email','phone'])->first()->makeHidden(['full_name','name']);

            // Check Already Pending Request
            $payout = Payout::where('user_id', auth()->id())->whereStatus(Payout::STATUS_INREVIEW)->first();
            if ($payout) {
                return $this->errorOk('Sorry you are not allowed to make this request since you already have one pending request.');
            } else {
                if ($request->get('type') == "Bank") {
                    $type = Payout::TYPE_BANK;
                } else {
                    $type = Payout::TYPE_UPI;
                }

                Payout::create(
                    [
                    'user_id' => auth()->id(),
                    'bank_details' => $bank_details,
                    'user_details' => $user_details,
                    'type' => $type,
                    'amount' => $request->amount,
                    ]
                );
            }
            return $this->successMessage('Your payout request has been taken successfully.');
        } catch (\Exception | \Error $e) {
            return $this->errorOk('Something went wrong!'. $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $payout = Payout::where('id', $id)->first();
            if (!$payout) {
                return $this->errorOk('Payout not found!');
            }
            return $this->success($payout);
        } catch (\Throwable $th) {
            return $this->errorOk('Something went wrong!'. $e->getMessage());
        }
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
        //
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

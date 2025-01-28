<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\UserSubscription;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $subscriptions = Subscription::where('id', '!=', 1)->get();
            if ($subscriptions) {
                return $this->success($subscriptions);
            } else {
                return $this->errorOk('Subscription data is not available!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkoutIndex(Request $request, $id)
    {
        try {
            $subscription = Subscription::where('id', $id)->first();
            $to_date = now()->addDays($subscription->duration);
            if ($subscription) {
                UserSubscription::create(
                    [
                    'user_id' => auth()->id(),
                    'subscription_id' => $id,
                    'from_date' => now(),
                    'to_date' => $to_date,
                    ]
                );
                return $this->success('Subscription Created successfully!');
            } else {
                return $this->errorOk('Subscription not found');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   

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
        $data['user_id'] =  $subscription->user_id;
        $data['title'] = "Your Payment Status has been updated by admin";
        $data['link'] = route('user.order.index');
        $data['notification'] = 'Your Payment has been '.$order->payment_status_parsed->label.' by admin!';
        pushOnSiteNotification($data);
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

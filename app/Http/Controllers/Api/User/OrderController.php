<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Item;

class OrderController extends Controller
{
    protected $now;
    private $resultLimit = 10;

    public function __construct()
    {
        $this->now = \Carbon\Carbon::now();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $page = $request->has('page') ? $request->get('page') : 1;
            $limit = $request->has('limit') ? $request->get('limit') : $this->resultLimit;
            $orders = Order::query();

            if ($request->has('from') && $request->has('to') && $request->get('from') && $request->get('to')) {
                $orders->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }
            if ($request->has('status') && $request->get('status') != null) {
                $orders->where('status', $request->get('status'));
            }
            $orders = $orders->where('user_id', auth()->id())->with(
                ['user'=>function ($q) {
                    $q->select('id', 'first_name', 'last_name', 'avatar', 'email', 'phone', 'gender', 'country_id', 'state_id', 'city_id')->with(
                        ['country'=>function ($q) {
                            $q->select('id', 'name');
                        },'state'=>function ($q) {
                            $q->select('id', 'name');
                        },'city'=>function ($q) {
                            $q->select('id', 'name');
                        }]
                    );
                },'orderitem'=>function ($qa) {
                    $qa->with(
                        ['item'=>function ($q) {
                            $q->with('category');
                        }]
                    );
                }]
            )->latest()->limit($limit)
            ->offset(($page - 1) * $limit)->get();
            if ($orders) {
                return $this->success($orders);
            } else {
                return $this->success([]);
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
    public function show($id)
    {
        try {
            $order = Order::where('id', $id)->with(
                ['user'=>function ($q) {
                    $q->select('id', 'first_name', 'last_name', 'avatar', 'email', 'phone', 'gender', 'country_id', 'state_id', 'city_id')->with(
                        ['country'=>function ($q) {
                            $q->select('id', 'name');
                        },'state'=>function ($q) {
                            $q->select('id', 'name');
                        },'city'=>function ($q) {
                            $q->select('id', 'name');
                        }]
                    );
                },'orderitem'=>function ($qa) {
                    $qa->with(
                        ['item'=>function ($q) {
                            $q->with('category');
                        }]
                    );
                }]
            )->first();
            if (!$order) {
                return $this->errorOk('Order data not found');
            }
            return $this->success($order);
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
    
    public function invoice($id)
    {
        try {
            $order = Order::where('id', $id)->with(
                ['user'=>function ($q) {
                    $q->select('id', 'first_name', 'last_name', 'avatar', 'email', 'phone', 'gender', 'country_id', 'state_id', 'city_id')->with(
                        ['country'=>function ($q) {
                            $q->select('id', 'name');
                        },'state'=>function ($q) {
                            $q->select('id', 'name');
                        },'city'=>function ($q) {
                            $q->select('id', 'name');
                        }]
                    );
                },'orderitem'=>function ($qa) {
                    $qa->with(
                        ['item'=>function ($q) {
                            $q->with('category');
                        }]
                    );
                }]
            )->first();
            if (!$order) {
                return $this->errorOk('Order data not found');
            }
            return $this->success($order);
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            return $this->createOrder($request);
            return $this->success("Order placed successfully");
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function orderRefund(Request $request, $id)
    {
        try {
            $order = Order::where('id', $id)->first();
            if (!$order) {
                return $this->errorOk('No order found!');
            }
            $order->update(
                [
                'status' => 5,
                'payment_status' => 3,
                ]
            );
            return $this->successMessage('Order Refunded Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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

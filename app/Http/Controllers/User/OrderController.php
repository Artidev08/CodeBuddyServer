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
use App\Models\Order;
use App\Models\Item;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Orders';
    }
    public function index()
    {
        $orders = Order::whereUserId(auth()->id())->latest()->simplePaginate(10);
        $items = Item::get();
        $address = UserAddress::whereUserId(auth()->id())->get();
        $checkAddress = UserAddress::whereUserId(auth()->id())->first();
        if (!$checkAddress) {
            $address = storeDefaultAddress(auth()->id());
            $address = array($address);
        }
        $label = $this->label;
        return view('user.order.index', compact('orders', 'label', 'items', 'address'));
    }


    public function invoice($id)
    {
        $order = Order::whereId($id)->first();
        return view('user.order.invoice', compact('order'));
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
        $input = $request->all();
        $data = $this->createOrder($input);
        return back()->with('success', 'your order created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Order        $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function getItem(Request $request)
    {
        $item = Item::where('id', $request->itemId)->first();
        return $item;
    }
}

<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\UserAddress;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;

trait ControlOrder
{
    public function createOrder($request)
    {
        $subTotal = 0;
        $total = 0;
        $totalTax = 0;
        
      
        if ($request['request_with'] == 'web') {
            $items_chk = Item::whereIn('id', $request['items'])->get();
            if($items_chk->count() > 0) 
                $to_chk = UserAddress::where('user_id', $items_chk[0]->user_id)->first();
            else
                $to_chk = getSellerAddresses($request['to'], $request);
            $from_chk = UserAddress::where('user_id', $request['user_id'])->first();
            $request['status'] = 0;
        } else {
            $from_chk = UserAddress::where('user_id', $request->from)->first();
            $to_chk = UserAddress::where('user_id', $request->to)->first();
            $items_chk = Item::whereIn('id', json_decode($request->items, true))->get();
            if ($items_chk->count() != count(json_decode($request->items, true))) {
                return $this->error("Some of these items are not found");
            }
        }
        if (!$from_chk) 
            $from_chk = storeDefaultAddress($request['user_id'], $request);

        if (!$to_chk) 
            $to_chk = getSellerAddresses($request['to'], $request);
        // Order Record
        $order = new Order;
        $order->user_id = isset($request['user_id']) ?  $request['user_id'] : auth()->id();
        $order->type = isset($request->type) ? $request->type : 'Item';
        $order->type_id = isset($request->type_id) ? $request->type_id : getAdminId();
        $order->total = isset($request->total) ?  $request->total :  $total;
        $order->discount = isset($request->discount) ? $request->discount: $request['discount'];
        $order->sub_total = isset($request->sub_total) ?  $request->sub_total : $subTotal;
        $order->from = $from_chk->details;
        $order->to = @$to_chk->details??$to_chk;
        $order->shipping = isset($request->shipping) ?  $request->shipping : 0;
        $order->date = now()->format('Y-m-d');
        $order->remarks = "API Order Placed";
        $order->status = isset($request->status) ? $request->status: 0;
        $order->payment_status = isset($request->payment_status) ? $request->payment_status : Order::PAYMENT_STATUS_UNPAID;
        $order->payment_gateway = isset($request->payment_gateway) ? $request->payment_gateway :$request['payment_gateway'] ;
        $order->txn_no = getTxnCode();
        $order->save();
        // Order Items Record
        $qty = $request->qty??1;
        foreach ($items_chk as $key => $item) {
            $rate = round($item->sell_price/(1+($item->tax_percent/100)),2); // $inclusivePrice - ($inclusivePrice / (1 + $taxRateDecimal));
            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->item_type = "Item";
            $order_item->item_id = $item->id;
            $order_item->qty = $qty;
            $order_item->price = $item->sell_price;
            $order_item->rate = $rate;
            $order_item->tax_percent = $item->tax_percent;
            $order_item->excl_total = $item->sell_price * $qty;
            $order_item->incl_total = $rate * $qty;
            $order_item->save();
            $subTotal += $item->sell_price * $qty;
        }

        $subTotal = OrderItem::where('order_id', $order->id)->sum('excl_total');
        $total =  $subTotal - $request['discount'] + $order->shipping;
        $order->update(
            [
            'sub_total' => $subTotal,
            'total' => $total,
            ]
        );
        return OrderItem::latest()->first();
    }
}

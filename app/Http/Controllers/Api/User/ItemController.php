<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Cart;
use App\Models\MyWishlist;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        try {
            $items = Item::query();
            if (request()->has('search') && request()->get('search')) {
                $items->where('name', 'like', '%' . request()->get('search') . '%');
            }
            $items = $items->with(
                ['category'=>function ($q) {
                    $q->select('id', 'name');
                }]
            )->get();
            if ($items) {
                return $this->success($items);
            } else {
                return $this->success([]);
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
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
            $item = Item::where('id', $id)->first();
            if ($item) {
                return $this->success($item);
            } else {
                return $this->errorOk('Item data not found!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    public function cartIndex(Request $request)
    {
        try {
            $cart = Cart::where('user_id', auth()->id())->with('item', 'user')->get();
            if ($cart) {
                return $this->success($cart);
            } else {
                return $this->errorOk('No cart data found!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
    public function myWishlist(Request $request)
    {
        try {
            $wishlist = MyWishlist::where('user_id', auth()->id())->get();
            if ($wishlist) {
                return $this->success($wishlist);
            } else {
                return $this->errorOk('No wishlist data found!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
    public function wishlistAddItem(Request $request, $id)
    {
        try {
            $item = Item::where('id', $id)->first();
            if (!$item) {
                return $this->errorOk('Item was not fornd!');
            }
            if ($item) {
                $meta = [
                    'item_name' => $item->name,
                    'item_price' => $item->mrp_price,
                    'item_sell_price' => $item->sell_price,
                ];
                MyWishlist::create(
                    [
                    'user_id' => auth()->id(),
                    'type' => 'Item',
                    'type_id' => $item->id,
                    'meta' => $meta,
                    ]
                );
                return $this->success('Wishlist Added Successfully!');
            } else {
                return $this->errorOk('No wishlist data found!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function wishlistRemove($id)
    {
        try {
            $wishlist = MyWishlist::where('id', $id)->first();
            if (!$wishlist) {
                return $this->errorOk('Wishlist data not found!');
            }
            $wishlist->delete();
            return $this->successMessage('Wishlist Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function productCheckout(Request $request)
    {
        try {
            $cartData = Cart::where('user_id', auth()->id());
            $sum = $cartData->sum('total');
            $price = $cartData->sum('price');
            if (!$cartData->exists()) {
                return $this->errorOk('No Cart data yet!');
            }
            $order = order::create(
                [
                'user_id' => auth()->id(),
                'type_id' => auth()->id(),
                'type' => $cartData->item_type,
                'status' => 1,
                'payment_status' => 2,
                'total' => $price,
                'sub_total' => $sum,
                ]
            );
            foreach ($cartData->get() as $cart) {
                OrderItem::create(
                    [
                    'order_id' => $order->id,
                    'item_type' => $cart->type,
                    'item_id' => $cart->type_id,
                    'qty' => $cart->qty,
                    'price' => $cart->price,
                    ]
                );
                $cart->delete();
            }
            return $this->successMeassage('Order Placed Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
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

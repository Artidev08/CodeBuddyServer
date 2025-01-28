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
use App\Models\Order;
use App\Models\User;
use App\Models\Item;
use App\Models\Country;
use App\Models\UserAddress;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade;
use PDF;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Orders';
    }
    public function index(OrderRequest $request)
    {
        // return $request->all();
        if (!$request->has('from') && !$request->has('to')) {
            $start_date = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
            return redirect(route('admin.orders.index')."?from=$start_date&to=$end_date");
        }
         $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
         $orders = Order::query();
        if (request()->get('search')) {
            $orders->where('id', 'like', '%'.request()->get('search').'%')
                ->orWhere('txn_no', 'like', '%'.request()->get('search').'%')
                ->orWhere('total', 'like', '%'.request()->get('search').'%')
                ->orWhereHas(
                    'user',
                    function ($q) {
                        $q->where(\DB::raw('CONCAT(first_name, " ",last_name)'), 'LIKE', '%' .request()->get('search'). '%')
                            ->orWhere('first_name', 'like', '%'.request()->get('search').'%')
                            ->orWhere('last_name', 'like', '%'.request()->get('search').'%');
                    }
                )
                ->orWhereHas(
                    'orderItems',
                    function ($q) {
                        $q->whereHas('item',function ($item) {
                            $item->where('name', 'like', '%'.request()->get('search').'%')
                            ->orWhere('slug', 'like', '%'.request()->get('search').'%')          
                            ->orWhere('sku', 'like', '%'.request()->get('search').'%');
                        });
                       
                    }
                );
        }
        if ($request->get('from') && $request->get('to')) {
            $orders->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
            $statuses = Order::STATUSES;
            $payment_statuses = Order::PAYMENT_STATUS;
        if ($request->get('asc')) {
            $orders->orderBy($request->get('asc'), 'asc');
        }
        if ($request->has('status') && $request->get('status') != null) {
            $orders->where('status', $request->get('status'));
        }
        if ($request->has('payment_status') && $request->get('payment_status') != null) {
            $orders->where('payment_status', $request->get('payment_status'));
        }
        if ($request->has('user') && $request->get('user') != null && $request->get('user') != 'all') {
            $orders->whereUserId($request->get('user'));
        }
        // return dd($orders->get());
        if ($request->get('desc')) {
            $orders->orderBy($request->get('desc'), 'desc');
        }
            $orders = $orders->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.orders.load', ['orders' => $orders])->render();
        }
            $label = $this->label;
        return view('admin.orders.index', compact('orders', 'statuses', 'label', 'payment_statuses'));
    }


    public function print(Request $request)
    {
        $orders_arr = collect($request->records['data'])->pluck('id');
        $orders = Order::whereIn('id', $orders_arr)->get();
        return view('admin.orders.print', ['orders' => $orders])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $label = 'Order';
            $countries = Country::all();
            return view('admin.orders.create', compact('label', 'countries'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
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
        try {
            //create new order
            $this->createOrder($request);
            return redirect()->route('admin.orders.index')->with('success', 'Order Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try{
            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }
            $order = Order::whereId($id)->firstOrFail();
            $label = 'Order';
            // Fetch all record using relation
            $items = $order->orderItems;
            $statuses = Order::STATUSES;
            //    $taxes = json_decode($order->tax);
            return view('admin.orders.show', compact('order', 'label', 'items', 'statuses'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function updateStatus(Request $request, Order $order)
    {
        try {
            if ($order) {
                $order->update(['status' => $request->status, "updated_by" => auth()->id()]);
                //Order Status Notification
                    $data['user_id'] =  $order->user_id;
                    $data['title'] = "Order has been ".$order->status_parsed->label;
                    $data['link'] = route('user.order.index');
                    $data['notification'] = 'Your Order '.$order->status_parsed->label.' successfully!';
                    pushOnSiteNotification($data);
                    return back()->with('success', 'Status updated Successfully');
            }
            return back()->with('error', 'Story Not Found');
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function updatePaymentStatus(Request $request, Order $order)
    {
        
        try {
            if ($order) {
                $order->update(['payment_status' => $request->payment_status, "updated_by" => auth()->id()]);
                // Notification Update Payment Status
                    $data['user_id'] =  $order->user_id;
                    $data['title'] = "Payment Status Updated";
                    $data['link'] = route('user.order.index');
                    $data['notification'] = 'Your Payment status updated by admin to '. Order::PAYMENT_STATUS[$order->payment_status]['label'].'!';
                    pushOnSiteNotification($data);
                return back()->with('success', 'Status updated Successfully');
            }
            return back()->with('error', 'Story Not Found');
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function invoice(Order $order)
    {
        try {
            $label = 'Order';
            $orderItems = $order->orderItems;
            return view('admin.orders.invoice-pdf', ['order' => $order,'orderItems' => $orderItems]);
            $pdf = PDF::loadView('admin.orders.invoice-pdf', ['order' => $order,'orderItems' => $orderItems]);
            return $pdf->download('ORD'.$order->id.'-invoice.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function deliveryReceipt(Order $order)
    {
        // return 's';
        try {
            $label = 'Order';
            $items = $order->orderItems;
            return view('admin.orders.delivery-receipt', compact('order', 'label', 'items'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
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
        // TODO: Allow only allowed permission user to do this action

        // Item Deletion
        $order_items = $order->orderItems;
        if ($order_items->count() > 0) {
            foreach ($order_items as $key => $order_item) {
                $order_item->delete();
            }
        }

        // Parent Deletion
        $order->delete();
    }

    public function bulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                        'status'=>'error',
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    $orders = Order::whereIn('id', $request->ids)->get();
                    foreach ($orders as $key => $order) {
                        $this->destroy($order);
                    }

                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                case ('columnUpdate'):
                    Order::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
    
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('status'):
                            $html['badge_color'] = Order::STATUSES[$request->value]['color'];
                            $html['badge_label'] = Order::STATUSES[$request->value]['label'];
    
                            $title = "Updated ".count($request->ids)." records successfully!";
                            break;
                        case ('payment_status'):
                            $html['badge_color'] = Order::PAYMENT_STATUS[$request->value]['color'];
                            $html['badge_label'] = Order::PAYMENT_STATUS[$request->value]['label'];
    
                            $title = "Updated ".count($request->ids)." records successfully!";
                            break;
                        default:
                            $type = "error";
                            $title = 'No action selected!';
                    }
                    
                    break;
                default:
                    $type = "error";
                    $title = 'No action selected!';
            }
            
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'column'=>$request->column,
                        'action'=>$request->action,
                        'data' => $request->ids,
                        'title' => $title,
                        'html' => $html,
    
                    ]
                );
            }
        
            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
    
    public function getUser(Request $request)
    {
        $user = User::where('id', $request->userId)->first();
        $useraddress = UserAddress::where('user_id', $user->id)->first();
        return $useraddress;
    }
    public function getUserAddress(Request $request)
    {
        try {
            $userAddresses = UserAddress::where('user_id', $request->userId)->get();
            $html = '';
            foreach ($userAddresses as $userAddress) {
                $details = $userAddress ? $userAddress->details : '';
                $html .= '<option value="'.$userAddress->id.'">'. $details['name'].'|'.$details['address_1'].'|'.$details['city'].'|'.$details['pincode_id'].'</option>';
            }
            return response($html, 200);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
    public function getSellerAddress(Request $request)
    {
        try {
            $html = '';
            if ($request->itemId) {
                $item = Item::find($request->itemId);
                if ($item && $item->user_id) {
                    $userAddresses = UserAddress::where('user_id', $item->user_id)->get();
                    foreach ($userAddresses as $userAddress) {
                        $details = $userAddress ? $userAddress->details : '';
                        $html .= '<option value="'.$userAddress->id.'">'. $details['name'].'|'.$details['address_1'].'|'.$details['city'].'|'.$details['pincode_id'].'</option>';
                    }
                }
            }
            return response(['html'=>$html,'user_id'=>$item->user_id, 200]);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }
}

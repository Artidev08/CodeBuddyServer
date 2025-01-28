<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\UserSubscription;

class SubscriptionController extends Controller
{
    public $label;
    public function __construct()
    {
        $this->label = 'Subscription Plans';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
        
         $length = 10;
         if(request()->get('length')){
             $length = $request->get('length');
         }
         $subscriptions = Subscription::query();
            if($request->get('search')){
                $subscriptions->where('id','like','%'.$request->search.'%')
                ->orWhere('name','like','%'.$request->search.'%');
            }
            // if($request->get('type')){
            //     $subscriptions->where('is_published','=',$request->type);
            // }
            if($request->has('type') && $request->get('type') != null){
                $subscriptions->where('is_published',$request->get('type'));
            }
            if($request->get('from') && $request->get('to')) {
                $subscriptions->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }

        if ($request->get('asc')) {
            $subscriptions->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $subscriptions->orderBy($request->get('desc'), 'desc');
        }
           $subscriptions = $subscriptions->latest()->paginate($length);

        if ($request->ajax()) {
            return view('admin.subscriptions.load', ['subscriptions' => $subscriptions])->render();
        }

           $label = $this->label;
 
           return view('admin.subscriptions.index', compact('subscriptions', 'label'));
    }

    
        // public function print(Request $request){
        //     $subscriptions = collect($request->records['data']);
        //         return view('admin.subscriptions.print', ['subscriptions' => $subscriptions])->render();
           
        // }

    public function print(Request $request)
    {
        $subscriptions_arr = collect($request->records['data'])->pluck('id');
        $subscriptions = Subscription::whereIn('id', $subscriptions_arr)->get();
        return view('admin.subscriptions.print', ['subscriptions' => $subscriptions])->render();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.subscriptions.create');
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
        try {
            $this->validate(
                $request,
                [
                'name'     => 'required',
                'duration'     => 'required|max:1000',
                'price'     => 'required|max:5',
                ]
            );
            if (!$request->has('is_published')) {
                $request['is_published'] = 0;
            }
            $subscription = Subscription::create($request->all());
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Subscription created successfully'
                    ]
                );
            }

            return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        try {
            return view('admin.subscriptions.show', compact('subscription'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $subscription = Subscription::whereId($id)->firstOrFail();

            return view('admin.subscriptions.edit', compact('subscription'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $this->validate(
            $request,
            [
            'name'     => 'required',
            'duration'     => 'required',
            'price'     => 'required',
            // 'is_published'     => 'required',
            ]
        );

        try {
            if (!$request->has('is_published')) {
                $request['is_published'] = 0;
            }
            if ($subscription) {
                $chk = $subscription->update($request->all());
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Subscription created successfully'
                        ]
                    );
                }
            }
            return back()->with('error', 'Subscription not found')->withInput($request->all());
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        try {
            if ($subscription) {
                $userSubscription = UserSubscription::where('subscription_id', $subscription->id)->first();
                if ($userSubscription) {
                    return back()->with('error', 'You cannot delete this subscription since it is assigned to the user');
                }
                $subscription->delete();
                return back()->with('success', 'Subscription deleted successfully');
            } else {
                return back()->with('error', 'Subscription not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
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
                    Subscription::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                case ('columnUpdate'):
                    Subscription::whereIn('id', $request->ids)->update(
                        [
                        $request->column => $request->value
                        ]
                    );
    
                    switch ($request->column) {
                        // Column Status Output Generation
                        case ('is_published'):
                            $html['badge_color'] = $request->value != 0 ? "success" : "danger";
                            $html['badge_label'] = $request->value != 0 ? "Published" : "Unpublished";
    
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
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}

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

use App\Models\WebsiteEnquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteEnquiryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsiteEnquiryController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Enquiry';
    }
    
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $websiteEnquiries = WebsiteEnquiry::query();
        if ($request->get('search')) {
            $websiteEnquiries->where('name', 'like', '%'.$request->get('search').'%')
                ->orWhere('status', 'like', '%'.$request->get('search').'%')
                ->orWhere('subject', 'like', '%'.$request->get('search').'%')
                ->orWhere('description', 'like', '%'.$request->get('search').'%')
                -> orWhere('phone', 'like', '%'.$request->get('search').'%');
        }
        if ($request->get('from') && $request->get('to')) {
            $websiteEnquiries->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if (request()->has('status') && request()->get('status') != null) {
            $websiteEnquiries->where('status', request()->get('status'));
        }
        $statuses = WebsiteEnquiry::STATUSES;
        $websiteEnquiries= $websiteEnquiries->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.website-enquiries.load', ['websiteEnquiries' => $websiteEnquiries])->render();
        }
        $label = $this->label;
        return view('admin.website-enquiries.index', compact('websiteEnquiries', 'label', 'statuses'));
    }

    public function print(Request $request)
    {
        $websiteEnquiries_arr = collect($request->records['data'])->pluck('id');
        $websiteEnquiries = WebsiteEnquiry::whereIn('id', $websiteEnquiries_arr)->latest()->get();
        return view('admin.website-enquiries.print', ['websiteEnquiries' => $websiteEnquiries])->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $label = Str::singular($this->label);
        return view('admin.website-enquiries.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebsiteEnquiryRequest $request)
    {
        // try {
            // return $request->all();
            $websiteEnquiry = new WebsiteEnquiry();
            $websiteEnquiry->name = $request->name;
            $websiteEnquiry->status = $request->status;
            $websiteEnquiry->subject = $request->subject;
            $websiteEnquiry->type = $request->type;
            $websiteEnquiry->email = $request->email;
            $websiteEnquiry->phone  = $request->phone;
            // $websiteEnquiry->value_type = $request->value_type;
            $websiteEnquiry->description = $request->description;
            $websiteEnquiry->save();
        if (request()->ajax()) {
            return response()->json(
                [
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'User Enquiry created successfully!'
                ]
            );
        }
            return redirect()->route('admin.website-enquiries.index')->with('success', 'User Enquiry created successfully.');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebsiteEnquiry $userEnquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $websiteEnquiry = WebsiteEnquiry ::whereId($id)->firstOrFail();
        return view('admin.website-enquiries.show', compact('websiteEnquiry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebsiteEnquiry $userEnquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $websiteEnquiry = WebsiteEnquiry::whereId($id)->firstOrFail();
        $label = Str::singular($this->label);
        return view('admin.website-enquiries.edit', compact('websiteEnquiry', 'label'));
    }
    public function status(WebsiteEnquiry $websiteEnquiry, Request $request)
    {
        try {
            if ($websiteEnquiry) {
                $websiteEnquiry->update(['status' => $request->status, "updated_by" => auth()->id()]);
                return back()->with('success', 'Status updated Successfully');
            }
            return back()->with('error', 'Story Not Found');
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\WebsiteEnquiry $userEnquiry
     * @return \Illuminate\Http\Response
     */
    public function update(WebsiteEnquiryRequest $request, WebsiteEnquiry $websiteEnquiry)
    {
        try {
            $websiteEnquiry->name=$request->name ?? '';
            $websiteEnquiry->status=$request->status ?? '';
            $websiteEnquiry->subject=$request->subject ?? '';
            $websiteEnquiry->description=$request->description ?? '';
            $websiteEnquiry->type=$request->type ?? '';
            $websiteEnquiry->email=$request->email ?? '';
            $websiteEnquiry->phone=$request->phone ?? '';
            $websiteEnquiry->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Website Enquiry update successfully!'
                    ]
                );
            }
            return redirect(route('admin.website-enquiries.index'))->with('success', 'Website Enquiry update successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function BulkAction(Request $request)
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
                    WebsiteEnquiry::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
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
                    ]
                );
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function destroy(WebsiteEnquiry $websiteEnquiry)
    {
        try {
            if ($websiteEnquiry) {
                $websiteEnquiry->delete();
                return back()->with('success', 'Website Enquiry Deleted Successfully!');
            } else {
                return back()->with('error', 'SupportTicket not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}

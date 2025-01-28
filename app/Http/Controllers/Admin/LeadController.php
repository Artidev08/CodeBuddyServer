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

use App\Models\Lead;
use App\Models\UserNote;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Leads';
    }
    public function index(Request $request)
    {
        // if(!auth()->user()->isAbleTo('view_leads')){
        //     return back()->with('error', 'Permision Denied');
        // }
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $leads = Lead::query();
        if ($request->get('from') && $request->get('to')) {
            $leads->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->get('lead_type_id')) {
            $leads->where('lead_type_id', $request->lead_type_id);
        }
        if ($request->get('lead_source_id')) {
            $leads->where('lead_source_id', $request->lead_source_id);
        }
        if ($request->get('search')) {
            $leads->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('owner_email', 'like', '%'.$request->search.'%');
        }
        if ($request->get('asc')) {
            $leads->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $leads->orderBy($request->get('desc'), 'desc');
        }
        $leads = $leads->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.leads.load', ['leads' => $leads])->render();
        }
        $source_categories = getCategoriesByCode('LeadSource');
        $status_categories = getCategoriesByCode('LeadStatus');
        $label = $this->label;
        return view('admin.leads.index', compact('leads', 'source_categories','status_categories', 'label'));
    }

   
    public function print(Request $request)
    {
        $leads_arr = collect($request->records['data'])->pluck('id');
        $leads = Lead::whereIn('id', $leads_arr)->latest()->get();
        return view('admin.leads.print', ['leads' => $leads])->render();
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $source_categories = getCategoriesByCode('LeadSource');
        $status_categories = getCategoriesByCode('LeadStatus');
        $type_categories = getCategoriesByCode('LeadCategories');

        $label = Str::singular($this->label);
        return view('admin.leads.create', compact('source_categories', 'label', 'type_categories', 'status_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadRequest $request)
    {
        //  return $request->all();
        // try {
            $data = new Lead();
            $data->user_id=$request->user_id;
            $data->name=$request->name;
            $data->category_id=$request->category_id;
            $data->lead_type_id=$request->lead_type_id;
            $data->lead_source_id=$request->lead_source_id;
            $data->owner_email=$request->owner_email;
            $data->phone=$request->phone;
            $data->remark=$request->remark;
            $data->address=$request->address;
            $data->save();
        if (request()->ajax()) {
            return response()->json(
                [
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Lead created successfully!'
                ]
            );
        }
            
            return redirect()->route('admin.leads.index')->with('success', 'Lead created successfully.');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $categories = getCategoriesByCode('LeadCategories');
        $jobTitleCategories = getCategoriesByCode('JobTitleCategories');
        $lead =Lead::whereId($id)->firstOrFail();
        return view('admin.leads.show', compact('lead', 'categories', 'jobTitleCategories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $lead = Lead ::whereId($id)->firstOrFail();
        $source_categories = getCategoriesByCode('LeadSource');
        $status_categories = getCategoriesByCode('LeadStatus');
        $type_categories = getCategoriesByCode('LeadCategories');
        
        $label = Str::singular($this->label);
        // $lead = Lead::whereId($id)->first();
      
        return view('admin.leads.edit', compact('lead', 'source_categories','status_categories', 'type_categories', 'label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Lead         $lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
        try {
            $lead->user_id=$request->user_id;
            $lead->name=$request->name;
            $lead->category_id=$request->category_id;
            $lead->lead_type_id=$request->lead_type_id;
            $lead->lead_source_id=$request->lead_source_id;
            $lead->owner_email=$request->owner_email;
            $lead->phone=$request->phone;
            $lead->remark=$request->remark;
            $lead->phone=$request->phone;
            $lead->website=$request->website;
            $lead->address=$request->address;
            $lead->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Lead update successfully!'
                    ]
                );
            }
            return redirect()->route('admin.leads.index')->with('success', 'Lead update successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {

        try {
            if ($lead) {
                // Notes Delete

                // Contacts Delete

                $lead->delete();
                return back()->with('success', 'Lead Deleted Successfully!');
            } else {
                return back()->with('error', 'Lead not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Lead $lead, Request $request)
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
                    Lead::whereIn('id', $request->ids)->delete();
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
            return response()->json(['error' => "Sorry! Action not found."], 401);
        }
    }
    public function moreAction(Request $request)
    {
        if (!$request->has('ids') || count($request->ids) <= 0) {
            return response()->json(['error' => "Please select atleast one record."], 401);
        }
        try {
            switch (explode('-', $request->action)[0]) {
                case 'status':
                    Lead::whereIn('id', $request->ids)->update([ 'status' => explode('-', $request->action)[1] ]);
                    return response()->json(
                        [
                        'message' => 'Status changed successfully.',
                        ]
                    );
                    break;
    
                case 'Move To Trash':
                    Lead::whereIn('id', $request->ids)->delete();
                    return response()->json(
                        [
                        'message' => 'Records moved to trashed successfully.',
                        ]
                    );
                    break;
    
                case 'Delete Permanently':
                    for ($i=0; $i < count($request->ids); $i++) {
                        $lead = Lead::withTrashed()->find($request->ids[$i]);
                        if ($lead->getMedia('image')->count()) {
                            $lead->clearMediaCollection('image');
                        }
                        $lead->forceDelete();
                    }
                    return response()->json(
                        [
                        'message' => 'Records deleted permanently successfully.',
                        ]
                    );
                    break;
    
                case 'Restore':
                    for ($i=0; $i < count($request->ids); $i++) {
                        $lead = Lead::withTrashed()->find($request->ids[$i]);
                        $lead->restore();
                    }
                    return response()->json(
                        [
                        'message' => 'Records restored successfully.',
                        ]
                    );
                    break;
    
                case 'Export':
                    return Excel::download(new LeadExport($request->ids), 'Lead-'.time().'.xlsx');
                    return response()->json(['error' => "Sorry! Action not found."], 401);
                    break;
                
                default:
                    return response()->json(['error' => "Sorry! Action not found."], 401);
                    break;
            }
        } catch (Exception $e) {
            return response()->json(['error' => "Sorry! Action not found."], 401);
        }
    }
}

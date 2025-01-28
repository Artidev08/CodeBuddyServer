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

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Chart;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use App\Exports\ChartDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\ExtractPdfToTextJob;
use Illuminate\Support\Facades\Http;
use App\Models\CategoryType;

class ChartController extends Controller
{

    public function index(Request $request){
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        // if()
        $charts = Chart::query();
        $charts->where('user_id',auth()->user()->id);
        if ($request->has('status') && $request->get('status') != null) {
            $charts->whereStatus([request()->get('status')]);
        }
        if ($request->has('project_id') && $request->get('project_id') != null) {
            $charts->where('project_id',request()->get('project_id'));
        }
        if ($request->get('search')) {
            $search = $request->get('search');
            $charts->where(
                function ($q) use ($search) {
                    $q->orWhereHas('entry',function($qu) use ($search){
                        $qu->where('id','like','%'.$search.'%');
                        $qu->where('name','like','%'.$search.'%');
                    });
                    $q->orWhereHas('auditor',function($qu) use ($search){
                        $qu->where('id','like','%'.$search.'%');
                        $qu->where('name','like','%'.$search.'%');
                    });
                    $q->orWhere('id', 'like', '%'.$search.'%');
                    $q->orWhere('name', 'like', '%'.$search.'%');
                }
            );
        }
        $divideLogics = getCategoriesByCode('DivideLogicCategories');
        $divideLogicType = CategoryType::whereCode('DivideLogicCategories')->first();
        $charts= $charts->withCount('entries')->latest()->paginate($length);
        $projects = Project::get();
        $headerLabel = 'Charts';

        if ($request->ajax()) {
            return view('member.charts.load', ['charts' => $charts,'projects'=>$projects])->render();
        }
        return view('member.charts.index', compact('charts','projects','divideLogics','divideLogicType','headerLabel'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            // 'pdf'     => 'required',
        ]);
        if (Session::has('last_pay_attempt')) {
            $last_attempt = Session::get('last_pay_attempt');
            $difference = $last_attempt->diffInMinutes(now());
            $seconds = 120-$last_attempt->diffInSeconds(now());
            if ($difference < 2) {
                if ($request->ajax()) {
                    return response()->json(['error'=>"Hold on, Please try after $seconds seconds."], 400);
                } else {
                    return redirect()->back()->with('error', "Hold on, Please try after $seconds seconds.")->withInput($request->all());
                }
            }
        }
        try {
            $file = $request->file('pdf');
            if($file){
                $request['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }

            if(empty($request->id)){
                $request['status'] = Chart::STATUS_PENDING;
                $request['user_id'] = auth()->user()->id;
                $chart = Chart::create($request->all());

                // call the next cron
                // $response = Http::get(url('cron/extract-pdf-text/HTFW24535'));
                // ExtractPdfToTextJob::dispatch($chart);
            }else{
                $chart = Chart::find($request->id);
                $oldStatus = $chart->status;
                // $oldStatusLabel = Chart::STATUSES[$chart->status]['label'];
                $chart->update($request->all());
                if($oldStatus != $request->status){
                    if(Chart::STATUS_REJECTED  == $chart->status)
                        $reason =  Category::whereId($request->select_reason)->value('name');
                    else
                        $reason =  $request->reason;
                    ActivityLog::create([
                        'model_id' => auth()->id(),
                        'model_type' => User::class,
                        'title' => auth()->user()->name." has changed status to ".Chart::STATUSES[$request->status]['label'],
                        'description' => $reason,
                        'related_id' => $chart->id,
                        'related_type' => Chart::class,
                    ]);
                }
            }
            if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
                if($chart->getMedia('pdf')->count() > 0)
                    $chart->clearMediaCollection('pdf');
                $chart->addMediaFromRequest('pdf')->toMediaCollection('pdf');
            }
            if ($request->ajax()) {
                syncCrons(0);

                return response([
                    'data'=>$chart,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Chart saved successfully!'
                ],200);
            }
            return back()->with('success', 'Chart saved successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Somthing went wrong'.$th->getMessage());
        }
    }
    public function bulkUpload(Request $request)
    {
        try {
            $request['status'] = Chart::STATUS_PENDING;
            $request['is_extract'] = 1;
            $request['workflow'] = 'automatic';
            $request['user_id'] = auth()->id();

            $file = $request->file('pdf');
            $request['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
                $chart = Chart::create($request->all());
                $chart->addMediaFromRequest('pdf')->toMediaCollection('pdf');
            }
            if ($request->ajax()) {
                return response([
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => $chart ? $chart->getPrefix():''.' Chart uploaded successfully!'
                ],200);
            }
            return back()->with('success', 'Chart saved successfully');
        } catch (\Throwable $th) {
            if ($request->ajax()) {
                return response([
                    'status'=>'error',
                    'message' => 'Error',
                    'title' => 'Somthing went wrong'.$th->getMessage()
                ],200);
            }
            return back()->with('error', 'Somthing went wrong'.$th->getMessage());
        }
    }
    public function exportEntries(Request $request,Chart $chart){
        $groupBy = null;
        if($request->has('group_by')){
            $groupBy = $request->get('group_by');
        }

        return Excel::download(new ChartDetailExport($chart->id,$groupBy), $chart->name.'-entries-'.time().'.xlsx');
    }
    

    public function destroy(Chart $Chart)
    {
        try {
            if ($Chart) {
                $Chart->delete();
                return back()->with('success', 'Chart and all associated resources deleted successfully!');
            } else {
                return back()->with('error', 'Chart not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request)
    {
        try {
            if(!isset($request->ids) || $request->ids == null){
                return response([
                    'status'=>'error',
                    'message' => 'Error',
                    'title' => 'Please select at least one row!'
                ],200);
            }

            $ids =  $request->ids;

            // Validate the request
            if ($ids == [""]) {
                if ($request->ajax()) {
                    return response([
                        'status'=>'error',
                        'message' => 'Error',
                        'title' => 'There were no rows selected by you!'
                    ],200);
                }
                return back()->with('error', 'There were no rows selected by you!');
            }
            foreach ($ids as $id) {
                if ($id != null) {
                    $chart = Chart::where('id', $id)->first();
                    $chart->delete();
                }
            }
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'action' => 'delete',
                        'data' => $request->ids,
                        'title' => 'Charts Deleted Successfully!',

                    ]
                );
            }
            return back()->with('success', 'Charts and all associated resources deleted successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}

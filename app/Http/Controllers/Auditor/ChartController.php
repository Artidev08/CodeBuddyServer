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

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Chart;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\User;
use App\Models\ChartDetail;

use App\Exports\ChartDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class ChartController extends Controller
{
    public function chartFind(Request $request){
        
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $chart = Chart::where('auditor_id',auth()->id())->whereIn('status', [Chart::STATUS_COMPLETED])->where('is_audit_needed',1)->first();
        if(empty($chart))
        $chart = Chart::whereIn('status', [Chart::STATUS_COMPLETED])->where('is_audit_needed',1)->where('auditor_id', null)->first();
        $chartDetails = ChartDetail::query();
        if($request->search){
            $chartDetails->where(function($q) use($request){
                $q->whereHas('doctor',function($qu) use($request){
                    $qu->where('name','like','%' . $request->search.'%');
                });
                $q->orWhere('from_dos','like','%' . $request->search.'%');
                $q->orWhere('to_dos','like','%' . $request->search.'%');
                $q->orWhere('dx','like','%' . $request->search.'%');
                $q->orWhere('record_type','like','%' . $request->search.'%');
                $q->orWhere('page_no','like','%' . $request->search.'%');
            });
        }
        $chartDetails = $chartDetails->where('chart_id', $chart->id)->paginate($length);
        if($request->ajax())
        return view('auditor.charts.load', compact('chartDetails','chart'));
        else{
            $authId = auth()->id();
            $chk = ActivityLog::where('model_type',USer::class)->where('model_id',$authId)->where('related_id',$chart->id)->where('related_type',Chart::class)->where('record_type',2)->whereDate('created_at',now())->first();
            if(!$chk){
                ActivityLog::create([
                    'model_id' => $authId,
                    'model_type' => User::class,
                    'title' => auth()->user()->name." has viewed chart ".$chart->getPrefix(),
                    'description' => null,
                    'related_id' => $chart->id,
                    'related_type' => Chart::class,
                    'record_type' =>2
                ]);
            }
            return view('auditor.charts.find', compact('chart','chartDetails'));
        }
    }
    public function statusBadge($id){
        $chart = Chart::find($id);
       return '<span class="badge badge-'. Chart::STATUSES[$chart->status]['color'] .'">'.  Chart::STATUSES[$chart->status]['label'] .'</span>';
    }
    public function review($id,Request $request){
        $chart = Chart::find($id);
        if($chart){
                $chart->auditor_id = auth()->id();
                $chart->save();
                return redirect(route('auditor.chart.show',$chart->id));
            
        }else
            return back()->with('error','chart not found!');
    }
    public function show($id,Request $request){
        $chart = Chart::where('id', $id)->first();
        $chartDetails = ChartDetail::query();
        if($request->search){
            $chartDetails->where(function($q) use($request){
                $q->whereHas('doctor',function($qu) use($request){
                    $qu->where('name','like','%' . $request->search.'%');
                });
                $q->orWhere('from_dos','like','%' . $request->search.'%');
                $q->orWhere('to_dos','like','%' . $request->search.'%');
                $q->orWhere('dx','like','%' . $request->search.'%');
                $q->orWhere('record_type','like','%' . $request->search.'%');
                $q->orWhere('page_no','like','%' . $request->search.'%');
            });
        }
        $chartDetails = $chartDetails->where('chart_id', $chart->id)->paginate();
        if($request->ajax())
        return view('auditor.charts.load', compact('chartDetails','chart'));
        else{
            $authId = auth()->id();
            $chk = ActivityLog::where('model_type',USer::class)->where('model_id',$authId)->where('related_id',$chart->id)->where('related_type',Chart::class)->where('record_type',2)->whereDate('created_at',now())->first();
            if(!$chk){
                ActivityLog::create([
                    'model_id' => $authId,
                    'model_type' => User::class,
                    'title' => auth()->user()->name." has viewed chart ".$chart->getPrefix(),
                    'description' => null,
                    'related_id' => $chart->id,
                    'related_type' => Chart::class,
                    'record_type' =>2
                ]);
            }
            return view('auditor.charts.find', compact('chart','chartDetails'));
        }
    }
    public function chartSearch(Request $request){
        $charts = Chart::query();
        if($request->get('search')){
            $charts->where(function($q) use($request){
                $q->orWhere('id', '=', $request->get('search'));
            });
        }
        $charts = $charts->whereIn('status',[Chart::STATUS_COMPLETED,Chart::STATUS_REJECTED,Chart::STATUS_PARKED])->where('is_audit_needed',1)->where(function($q){
           $q->where('auditor_id', null);
           $q->orWhere('auditor_id', auth()->id());
        })->take(1)->get();
        if($request->ajax())
        return view('auditor.charts.search-load', compact('charts'));
        else
        return view('auditor.charts.search', compact('charts'));
    }
    public function statusList($status,Request $request){
        
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $charts = Chart::query();
        if($request->get('search')){
            $search =   ltrim(ucwords($request->get('search')), 'Ch0');
            $charts->where(function($q) use($search){
                $q->orWhere('id','like','%' . $search . '%');
            });
        }
        $charts = $charts->where('auditor_id',auth()->id())->where('status', $status)->paginate($length);
        if($request->ajax())
        return view('auditor.charts.load-list', compact('charts','status')); 
        else
        return view('auditor.charts.list', compact('charts','status')); 
    }
    public function changeStatus(Request $request,$id,$status){
        try{
            $chart = Chart::findOrFail($id);
            $fromStatus = Chart::STATUSES[$chart->status]['label'];
            $chart->status = $status;
            $chart->save();
            $payload['status'] = [
                'from' => $fromStatus,
                'to' => Chart::STATUSES[$chart->status]['label'],
            ];
            ActivityLog::create([
                'model_id' => auth()->id(),
                'model_type' => User::class,
                'title' => auth()->user()->name." has changed status of ". $chart->getPrefix()." to ".Chart::STATUSES[$status]['label'],
                'description' => null,
                'related_id' => $chart->id,
                'related_type' => Chart::class,
                'payload' => $payload,
            ]);
            if($request->ajax()){
                return response([
                    'chart_status'=>$chart->status,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Status Changed successfully!'
                ],200);
            }
            return back()->with('success','Status Changed Successfully!');
        }catch(Exception $e){
            return back()->with('error', $e->getMessage());
        }

    }
    
    public function changeStatusReason(Request $request){
       
        $this->validate($request,[
            'reason' => 'required_without:select_reason',
            'select_reason' => 'required_without:reason',
            'status' => 'required',
            'id' => 'required',
        ]);
        try{
            $chart = Chart::findOrFail($request->id);
            $fromStatus = Chart::STATUSES[$chart->status]['label'];
            $chart->status = $request->status;
            $chart->save();
            if(Chart::STATUS_REJECTED  == $chart->status)
                $reason =  Category::whereId($request->select_reason)->value('name');
            else
                $reason =  $request->reason;
                $payload['status'] = [
                    'from' => $fromStatus,
                    'to' => Chart::STATUSES[$chart->status]['label'],
                ];
            ActivityLog::create([
                'model_id' => auth()->id(),
                'model_type' => User::class,
                'title' => auth()->user()->name." has changed status of " .$chart->getPrefix()." to ".Chart::STATUSES[$request->status]['label'],
                'description' => $reason,
                'related_id' => $chart->id,
                'related_type' => Chart::class,
                'payload' => $payload,
            ]);
            if($request->ajax()){
                return response([
                    'data'=>$chart,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Status updated successfully!'
                ],200);
            }
            return back()->with('success','Status Changed Sucessfully!');

        }catch(Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function logs(Request $request){
       $logs = ActivityLog::where('related_type',Chart::class)->where('related_id',$request->chart_id)->where('record_type',$request->record_type)->paginate(20);
       return view('common.charts.logs-data',compact('logs'))->render();
    }
    public function details(Request $request){
        $this->validate($request,[
            'doctor_id' => 'required',
            'from_dos' => 'required',
            'to_dos' => 'required',
            'dx' => 'required',
            'page_no' => 'required',
            'location' => 'required',
            'record_type' => 'required',
            'comments' => 'required',
        ]);

        ChartDetail::create($request->all());

        if($request->ajax())
            return response([
                'data'=>$chart,
                'status'=>'success',
                'message' => 'Success',
                'title' => 'Record added successfully!'
            ],200);
        else
        return back()->with('success','Record added successfully');
    }
    public function exportEntries(Chart $chart){
        pushActivityLog($chart,'Exported');
        return Excel::download(new ChartDetailExport($chart->id), 'DxEntries-'.time().'.xlsx');
    }
}

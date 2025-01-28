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

namespace App\Http\Controllers\Entry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Chart;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\ChartDetail;
use App\Models\User;
use App\Exports\ChartDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class ChartController extends Controller
{
    public function chartFind(Request $request){
        $chart = Chart::where('status', Chart::STATUS_PENDING)->where('entry_user_id', null)->first();
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
        return view('entry.charts.load', compact('chartDetails','chart'));
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
           
            return view('entry.charts.find', compact('chart','chartDetails'));
        }
    }
    public function assign($id,Request $request){
     
        $chart = Chart::find($id);
        if($chart){
            if($chart->status == Chart::STATUS_PENDING){
                $chk = Chart::where('entry_user_id',auth()->id())->where('status',Chart::STATUS_OPEN)->exists();
                if($chk){
                    return back()->with('error', "Open Chart already exists, so you have to complete it first!");
                }
                $chart->entry_user_id = auth()->id();
                $chart->status = Chart::STATUS_OPEN;
                $chart->save();
                return redirect(route('entry.chart.show',$chart->id));
            }
            if($chart){
                if($chart->entry_user_id != auth()->id()){
                    return redirect(route('entry.dashboard.index'))->with('error', "Chart is already assign to other user");
                }
            }
            return redirect(route('entry.chart.show',$chart->id));
        }else
            return back()->with('error','chart not found!');
    }
    
    public function show($id,Request $request){
        $chart = Chart::where('id', $id)->withCount('entries')->first();
        if($chart){
            if($chart->entry_user_id != auth()->id()){
                return redirect(route('entry.dashboard.index'))->with('error', "Chart is already assign to other user");
            }
        }
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
        return view('entry.charts.load', compact('chartDetails','chart'));
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
            return view('entry.charts.find', compact('chart','chartDetails'));
        }
    }
    public function statusBadge($id){
        $chart = Chart::find($id);
       return '<span class="badge badge-'. Chart::STATUSES[$chart->status]['color'] .'">'.  Chart::STATUSES[$chart->status]['label'] .'</span>';
    }
    public function chartSearch(Request $request){
        $charts = Chart::query();
        if($request->get('search')){
             $search =   ltrim(strtolower($request->get('search')), 'ch0');
             $charts->where(function($q) use($search){
                 $q->orWhere('id', '=', $search)->orWhere('name', '=', $search);
            });
        }else{
            $charts->whereId(0); 
        }
        $charts = $charts->whereIn('status',[Chart::STATUS_PENDING, Chart::STATUS_OPEN])->where(function($q){
            $q->where('entry_user_id', null);
            $q->orWhere('entry_user_id', auth()->id());
         })->withCount('entries')->take(1)->get();
        if($request->ajax())
        return view('entry.charts.search-load', compact('charts'));
        else
        return view('entry.charts.search', compact('charts'));
    }
    public function changeStatus($id,$status){
        try{
            if($status == Chart::STATUS_OPEN){
                $chk = Chart::where('entry_user_id',auth()->id())->where('status',Chart::STATUS_OPEN)->exists();
                if($chk){
                    return back()->with('error', "Open Chart already exists, so you have to complete it first!");
                }
            }
            $chartGap = auth()->user()->chart_gap;
            $charts = Chart::where('entry_user_id',auth()->id())->where('status',Chart::STATUS_COMPLETED)->latest()->take($chartGap)->get();
            $count = 0;
            foreach($charts as $chart){
                if($chart->is_audit_needed == 1)
                    $count++;
            }
            // check above records have is_audited 1 or not? user edit chat gap make user model label
            $chart = Chart::findOrFail($id);
            $fromStatus = Chart::STATUSES[$chart->status]['label'];
            $chart->status = $status;
            if($count == 0)
            $chart->is_audit_needed = 1;
            $chart->save();
            $payload['status'] = [
                'from' => $fromStatus,
                'to' => Chart::STATUSES[$chart->status]['label'],
            ];
            ActivityLog::create([
                'model_id' => auth()->id(),
                'model_type' => User::class,
                'title' => auth()->user()->name." has changed status of".$chart->getPrefix()." to ".Chart::STATUSES[$status]['label'],
                'description' => null,
                'related_id' => $chart->id,
                'related_type' => Chart::class,
                'payload' => $payload,
            ]);
            // if($request->ajax())
            //     return response([
            //         'status' =>'success',
            //         'msg' =>'Status Changed Sucessfully!'
            //     ]); 
            // else
            if(Chart::STATUS_OPEN == $status)
            return redirect(route('entry.chart.show',$chart->id));
            else
            return back()->with('success','Status Changed Sucessfully!');
        }catch(Exception $e){
            return back()->with('error', $e->getMessage());
        }

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
        $charts = $charts->where('entry_user_id',auth()->id())->where('status', $status)->paginate($length);
        if($request->ajax())
        return view('entry.charts.load-list', compact('charts','status')); 
        else
        return view('entry.charts.list', compact('charts','status')); 
    }

    public function changeStatusReason(Request $request){
        $this->validate($request,[
            'reason' => 'required_without:select_reason',
            'select_reason' => 'required_without:reason',
            'status' => 'required',
            'id' => 'required',
        ]);
        try{
            if(Chart::STATUS_PARKED == $request->status){
                if(User::PARKED_LIMITS[auth()->user()->parked_limit]['label']  <= Chart::where('status',Chart::STATUS_PARKED)->where('entry_user_id',auth()->id())->count()){
                    if($request->ajax()){
                        return response([
                            'data'=>null,
                            'status'=>'error',
                            'message' => 'error',
                            'title' => 'Chart Parked status limit exceed!'
                        ],200);
                    }else
                    return back()->with('error','Chart Parked status limit exceed!');
                }
            }
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
                'title' => auth()->user()->name." has changed status of".$chart->getPrefix()." to ".Chart::STATUSES[$request->status]['label'],
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
        $logs = ActivityLog::where('related_type',Chart::class)->where('related_id',$request->chart_id)->paginate(20);
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

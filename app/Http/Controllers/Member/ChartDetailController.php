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
use App\Models\Chart;
use App\Models\ChartDetail;
use Exception;

class ChartDetailController extends Controller
{

    public function index(Request $request){
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        // if()
        $charts = ChartDetail::query();
        $chart = null;
        if ($request->has('chart_id') && $request->get('chart_id') != null) {
            $chart = Chart::find($request->get('chart_id'));
            $charts->where('chart_id',request()->get('chart_id'));
        }

        if ($request->has('chunk_id') && $request->get('chunk_id') != null) {
            $charts->where('chunk_id',request()->get('chunk_id'));
        }
        
        if ($request->get('search')) {
            $search = $request->get('search');
            $charts->where(
                function ($q) use ($search) {
                    $q->orWhere('medication', 'like', '%'.$search.'%');
                    $q->orWhere('dx', 'like', '%'.$search.'%');
                    $q->orWhere('native_dx', 'like', '%'.$search.'%');
                    $q->orWhere('location', 'like', '%'.$search.'%');
                    $q->orWhere('from_dos', 'like', '%'.$search.'%');
                }
            );
        }
        $headerLabel = 'Chart Entries';

        $charts= $charts->orderBy('sequence','ASC')->latest()->paginate($length);
        if ($request->ajax()) {
            return view('member.chart-detail.load', ['charts' => $charts])->render();
        }
        return view('member.chart-detail.index', compact('charts','chart','headerLabel'));
    }

    public function edit($id)
    {
        try {
            $chartDetail = ChartDetail::findOrFail($id); // Ensure the record exists
            return view('member.chart-detail.edit', compact('chartDetail'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    

    public function update(Request $request, $id)
    {
        try {
            $chartDetail = ChartDetail::find($id);
            $request['hcc'] = [
                'rx' => $request->get('hcc-rx'),
                'cms' => $request->get('hcc-cms'),
                'esrd' => $request->get('hcc-esrd'),
            ];
            $request['native_hcc'] = [
                'rx' => $request->get('native-hcc-rx'),
                'cms' => $request->get('native-hcc-cms'),
                'esrd' => $request->get('native-hcc-esrd'),
            ];
            $chartDetail->update($request->all());

            return redirect()->route('member.chart-details.index',['chart_id' => $chartDetail->chart_id,'chunk_id' => $chartDetail->chunk_id])->with('success', 'Chart details updated successfully.');
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
                    $detail = ChartDetail::where('id', $id)->first();
                    // Delete the detail
                    $detail->delete();
                }
            }
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => 'success',
                        'action' => 'delete',
                        'data' => $request->ids,
                        'title' => 'Chart Detail Deleted Successfully!',

                    ]
                );
            }
            return back()->with('success', 'Detail Deleted Successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $detail = ChartDetail::where('id', $id)->first();
            if ($detail) {
                $detail->delete();
                return back()->with('success', 'Chart Detail deleted successfully');
            } else {
                return back()->with('error', 'Chart Detail not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

}

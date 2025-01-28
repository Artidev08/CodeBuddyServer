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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Models\ChartChunk;
use Exception;

class ChartChunkController extends Controller
{

    public function index(Request $request){
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        // if()
        $charts = ChartChunk::query();
        $chart = null;
        if ($request->has('chart_id') && $request->get('chart_id') != null) {
            $chart = Chart::find($request->get('chart_id'));
            $charts->where('chart_id',request()->get('chart_id'));
        }
        if ($request->get('search')) {
            $search = $request->get('search');
            $charts->where(
                function ($q) use ($search) {
                    $q->orWhere('id', 'like', '%'.$search.'%');
                }
            );
        }
        if ($request->has('status') && $request->get('status') != null) {
            $charts->where('status',request()->get('status'));
        }
        if ($request->has('entry_sync_status') && $request->get('entry_sync_status') != null) {
            $charts->where('entry_sync_status',request()->get('entry_sync_status'));
        }
        if ($request->has('mc_sync_status') && $request->get('mc_sync_status') != null) {
            $charts->where('mc_sync_status',request()->get('mc_sync_status'));
        }
        if ($request->has('native_sync_status') && $request->get('native_sync_status') != null) {
            $charts->where('native_sync_status',request()->get('native_sync_status'));
        }
        if ($request->has('location_sync_status') && $request->get('location_sync_status') != null) {
            $charts->where('location_sync_status',request()->get('location_sync_status'));
        }
        if ($request->has('rt_sync_status') && $request->get('rt_sync_status') != null) {
            $charts->where('rt_sync_status',request()->get('rt_sync_status'));
        }
        $charts= $charts->latest()->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.chart-chunk.load', ['charts' => $charts])->render();
        }
        return view('admin.chart-chunk.index', compact('charts','chart'));
    }
    public function edit($id)
    {
        try {
            $chartChunk = ChartChunk::findOrFail($id); // Ensure the record exists
            return view('admin.chart-chunk.edit', compact('chartChunk'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    

public function update(Request $request, $id)
{
    try {
        $chartChunk = ChartChunk::find($id);
        $chartChunk->update($request->all());

        return redirect()->route('admin.chart-chunks.index',['chart_id' => $chartChunk->chart_id])->with('success', 'Chart Chunk updated successfully.');
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
                    $detail = ChartChunk::where('id', $id)->first();
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
            $chunk = ChartChunk::find($id);
            if ($chunk) {
                $chunk->delete();
                return back()->with('success', 'Chunk deleted successfully');
            } else {
                return back()->with('error', 'Chunk not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

}

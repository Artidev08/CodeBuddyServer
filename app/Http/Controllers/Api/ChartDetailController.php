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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChartDetail;
use Illuminate\Http\Request;

class ChartDetailController extends Controller
{
    private $resultLimit = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $page = $request->has('page') ? $request->get('page') : 1;
            $limit = $request->has('limit') ? $request->get('limit') : $this->resultLimit;
            $chartDetails = ChartDetail::query();
            $chartDetails->where('is_delivered',ChartDetail::NOT_DELIVERED);

            // if ($request->get('from') && $request->get('to')) {
            //     $chartDetails->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            // }
            // if ($request->has('search') && $request->get('search')) {
            //     $chartDetails->where('doctor_id','LIKE','%'. $request->get('search') .'%');
            // }
            if ($request->has('chart_id') && $request->get('chart_id')) {
                $chartDetails->where('chart_id',$request->get('chart_id'));
            }
            // $chartDetails = $chartDetails->select('id','chart_id','doctor_id','from_dos','to_dos','dx','page_no','location','comments','record_type','is_delivered')->latest()->limit($limit)
            //  ->offset(($page - 1) * $limit)->get();
            $chartDetails = $chartDetails->select('id','chart_id','doctor_id','from_dos','to_dos','dx','page_no','location','comments','record_type','is_delivered')->latest()->first();
            if ($chartDetails) {
                return $this->success($chartDetails);
            } else {
                return $this->success(null);
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try{   
            $chartDetail = ChartDetail::find($id);  
            if($chartDetail){
                $this->validate($request, [
                    'is_delivered' => 'required',
                ]);
                $chartDetail = $chartDetail->update($request->all());
                return $this->success($chartDetail, 201);
            } else{
                return $this->error('Chart Detail Not Found');
            }
                
        } catch(\Exception $e){
            return $this->error("Error: " . $e->getMessage());
        }
    }

}

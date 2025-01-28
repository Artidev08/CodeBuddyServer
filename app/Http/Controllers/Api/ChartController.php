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
use App\Models\Chart;
use Illuminate\Http\Request;

class ChartController extends Controller
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
            $charts = Chart::query();
            $charts->where('entry_user_id',auth()->id())->orWhere('entry_user_id',null)->where('status',Chart::STATUS_PENDING);

            if ($request->get('from') && $request->get('to')) {
                $charts->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }
            if ($request->has('search') && $request->get('search')) {
                $charts->where('name','LIKE','%'. $request->get('search') .'%');
            }
            $charts = $charts->select('id','name','status','extracted_text','entry_user_id')->latest()->limit($limit)
             ->offset(($page - 1) * $limit)->get();
            if ($charts) {
                return $this->success($charts);
            } else {
                return $this->success([]);
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

}

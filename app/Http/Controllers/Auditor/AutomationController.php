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
use App\Models\ChartDetail;

class AutomationController extends Controller
{
    public function compareContent(Request $request,$chart_id){
        try{
            $chart = Chart::whereId($chart_id)->first();
            $orgs = json_decode(file_get_contents(public_path('json/org-directories.json')));
            // $orgs = json_decode(file_get_contents(public_path('json/org-directories.json')));
            $organizationName = '';
            foreach($orgs as $org){
                if(searchStringInsensitive($chart->extracted_text,$org)){
                    $organizationName = $org; 
                    break;
                }
            }
            $proceededContentArray = array_intersect_key($request->extract_content, array_flip($request->extract_content_keys));
            $result =  getExtractedContent($proceededContentArray);
            // return dd($result);
            if($request->ajax()){
                return response([
                    'data'=> view('auditor.charts.includes.extracted-data',compact('result','organizationName'))->render(),
                    'status'=>'success',
                    'message' => 'success',
                    'title' => 'Data Found!'
                ],200);
            }
            
            return $result;
        }catch(Exception $e){
            if($request->ajax()){
                return response([
                    'data'=>[],
                    'status'=>'error',
                    'message' => 'error',
                    'title' => 'Chart Parked status limit exceed!'
                ],200);
            }
            return back()->with('error','Something went wrong!');
        }
      
    }
}

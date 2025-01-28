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
use App\Models\CategoryType;
use App\Models\Category;
use App\Models\ChartDetail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ChartDetailController extends Controller
{
    protected  $carbonObj;
    function __construct() {
        $this->carbonObj = new Carbon();
    }

    public function edit($id){
        $chartDetail = ChartDetail::find($id);
        return view('entry.charts.edit',compact(['chartDetail']));
    }
    public function store(Request $request){
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
        $request['user_id'] = auth()->id();
        try{
            if(empty($request->id))
                $chartDetail = ChartDetail::create($request->all());
            else{
                $chartDetail = ChartDetail::find($request->id);
                $chartDetail->update($request->all());
            }
            $total_dx = ChartDetail::where('chart_id',$chartDetail->chart_id)->count();
            if($request->ajax())
                return response([
                    'data'=>$chartDetail,
                    'total_dx'=>$total_dx,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record added successfully!'
                ],200);
            else
            return back()->with('success','Record added successfully');
        }catch(Exception $e){
            if($request->ajax())
                return response([
                    'data'=>null,
                    'status'=>'error',
                    'message' => 'error',
                    'title' => $e->getMessage()
                ],200);
            else
            return back()->with('success',$e->getMessage());
        }
    }
    public function destroy($id,Request $request){
        $chartDetail = ChartDetail::find($id);
        if($chartDetail)
        $chartId = $chartDetail->chart_id;
        $chartDetail->delete();
        $total_dx = ChartDetail::where('chart_id',$chartId)->count();
            if($request->ajax())
                return response([
                    'data'=>null,
                    'total_dx'=>$total_dx,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record added successfully!'
                ],200);
        return back()->with('success','Record deleted successfully');
    }

    public function bulkStore(Request $request,$chartId)
    {
        // return $request->all(); 
         $this->validate($request,[
            'excel'=>'required',
        ]);
        try {
            $spreadsheet = IOFactory::load($request->file('excel'));
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }
            // return $rows;
            $rows = array_slice($rows,1);
            $master = $rows;
            // Indexing:
            $doctorName = 0;
            $fromDos = 1;
            $toDos = 2;
            $dx = 3;
            $pageNo = 4;
            $locationName = 5;
            $recordType  = 6;
            $comments  = 7;
            // Validation Loop
            foreach ($master as $index_temp => $item_temp) {
                    if($item_temp[$doctorName] == null )
                        return ($item_temp[$doctorName]." Doctor Name doesn't exists please create Doctor Name. Issue detected at row no. ".$rowNo);

                    if($item_temp[$fromDos] == null )
                        return ($item_temp[$fromDos]." From Dos doesn't exists please create From Dos. Issue detected at row no. ".$rowNo);

                    if($item_temp[$toDos] == null )
                        return ($item_temp[$toDos]." To Dos doesn't exists please create To Dos. Issue detected at row no. ".$rowNo);

                    if($item_temp[$dx] == null )
                        return ($item_temp[$categoryIndex]." Dx doesn't exists please create Dx. Issue detected at row no. ".$rowNo);
                    if($item_temp[$locationName] == null )
                        return ($item_temp[$locationName]." Location Name doesn't exists please create Location name. Issue detected at row no. ".$rowNo);

                    if($item_temp[$pageNo] == null )
                        return ($item_temp[$pageNo]." Page No doesn't exists please create Page No. Issue detected at row no. ".$rowNo);

                    if($item_temp[$recordType] == null )
                        return ($item_temp[$recordType]." Record Type doesn't exists please create Record Type. Issue detected at row no. ".$rowNo);
                    if($item_temp[$comments] == null )
                        return ($item_temp[$comments]." Comments doesn't exists please create Comments. Issue detected at row no. ".$rowNo);
                }
            
            $inputs = [];
            $authId = auth()->id();
            $now = now();
            $recordsCreated = 0;
            foreach ($master as $index_temp => $item_temp) {
                if ($item_temp[$doctorName] != null) {
                    // $category_type = CategoryType::where('code','DoctorNames')->first();
                    // $category = Category::where('category_type_id',$category_type->id)->where('name',$item_temp[$doctorName])->first();
                    // if(!$category){
                    //     $category = Category::create([
                    //         'name' => $item_temp[$doctorName],
                    //         'category_type_id' => $category_type->id,
                    //         'level' => 1,
                    //     ]);
                    // } 
                    $inputs[] = [
                        'chart_id' => $chartId,
                        'doctor_id' => $item_temp[$doctorName],
                        'from_dos' => $this->carbonObj->instance(Date::excelToDateTimeObject($item_temp[$fromDos]))->toDateString(),
                        'to_dos' => $this->carbonObj->instance(Date::excelToDateTimeObject($item_temp[$toDos]))->toDateString(),
                        'dx' =>$item_temp[$dx],
                        'location' => $item_temp[$locationName],
                        'page_no' => $item_temp[$pageNo],
                        'record_type' =>$item_temp[$recordType],
                        'comments' =>$item_temp[$comments],
                        'user_id' => $authId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $recordsCreated ++;
                }
            }
           
            if(!empty($inputs))
                ChartDetail::insert($inputs);
                $chart = Chart::find($chartId);
                pushActivityLog($chart,'Imported');

              if($request->ajax())
                return response([
                    'data'=> $recordsCreated,
                    'status'=>'success',
                    'message' => 'Records Created!',
                    'title' => $recordsCreated .' Records Created!'
                ],200);
            //  return back()->with('success','Chart added Successfully');
     } 
     catch (\Throwable $e) {
            return response([
                'data'=> null,
                'status'=>'error',
                'message' => 'Records not Created!',
                'title' =>'error'
            ],200);
       }
    }
 }
    



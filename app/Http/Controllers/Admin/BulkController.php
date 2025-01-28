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
use App\Http\Requests\BulkRequest;
use App\Models\User;
use App\Imports\UsersImport;
use App\Models\MedCodeVersion;
use App\Models\MedicalCondition;
use App\Models\SampleDocument;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class BulkController extends Controller
{
    public function user(BulkRequest $request,)
    {
        // return 's';
          Excel::import(new UsersImport, request()->file('file'));
        return redirect(route('admin.users.index', ['role' => 'Member']))->with('success', 'User added Successfully');
    }

     public function uploadMedical(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required'
        ]);
        $count = 0;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
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
        $head = array_shift($rows);
        $master = $rows;
        // Index
        $TitleIndex = 0;
        $CodeIndex = 1;
        $RXIndex = 2;
        $CMSIndex = 3;
        $ESRDIndex = 4;
        $ClassificationIndex = 5;

        $medical_obj = null;
        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;
            $title = MedicalCondition::whereTitle($item[$TitleIndex])->first();
            
            if($item[$TitleIndex] == null){
                return back()->with("error","Title Field is missing at Row:".$row_number." Please export again!");
            }
            if($item[$CodeIndex] == null){
                return back()->with("error","Code Field is missing at Row:".$row_number." Please export again!");
            }
            

            if(!$title && (trim($item[$TitleIndex]) != null || trim($item[$TitleIndex]) != '')  && $item[$CodeIndex] != null ){
                $existingRecord = MedicalCondition::where('title', $item[$TitleIndex])
                ->exists();
                if($existingRecord){
                    return back()->with('error', 'There is already a record with this title, at Row:'.$row_number.'  it should be unique!');
                }
                $details = [
                    'classification' => $item[$ClassificationIndex] ?? null,
                ];
                $medical_obj = MedicalCondition::create([ 
                    'title' => $item[$TitleIndex],
                    'code' => $item[$CodeIndex],
                    'hcc' => [
                        'rx' => $item[$RXIndex],
                        'cms' => $item[$CMSIndex],
                        'esrd' => $item[$ESRDIndex],
                    ],
                    'details' => json_encode($details),
                ]);
                
            }
        }

        return back()->with('success','Record Created Successfully!');
    }

   

     public function uploadMedCodeVersion(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required'
        ]);
        $count = 0;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
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
        $head = array_shift($rows);
        $master = $rows;
        // Index
        $FromCodeIndex = 0;
        $ToCodeIndex = 1;
        $RemarkIndex = 2;
        $medCodeVersion = null;
        
        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;
            if($item[$FromCodeIndex] == null){
                return back()->with("error","From is missing at Row:".$row_number." Please export again!");
            }
            if($item[$ToCodeIndex] == null){
                return back()->with("error","To Code Field is missing at Row:".$row_number." Please export again!");
            }
            if($item[$RemarkIndex] == null){
                return back()->with("error","Remark Field is missing at Row:".$row_number." Please export again!");
            }
            $existFromCode = MedCodeVersion::where('from_code',$item[$FromCodeIndex])->first();
            // if($existFromCode){
            //     return back()->with("error","This From Code is already exist at Row:".$row_number." Please export again!");
            // }
            $existToCode = MedCodeVersion::where('to_code',$item[$ToCodeIndex])->first();
            // if($existToCode){
            //     return back()->with("error","This To Code is already exist at Row:".$row_number." Please export again!");
            // }
            if((!$existFromCode) && (!$existToCode)){
                $medCodeVersion = MedCodeVersion::create([ 
                    'from_code' => $item[$FromCodeIndex],
                    'to_code' => $item[$ToCodeIndex],
                    'remark' => $item[$RemarkIndex],
                    // 'created_by' => auth()->id(),
                ]);
            }
                
        }

        return back()->with('success','Record Created Successfully!');
    }
}

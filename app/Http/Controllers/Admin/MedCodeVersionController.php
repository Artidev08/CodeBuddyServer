<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedCodeVersion;
use App\Models\MedicalCondition;
use App\Models\KeywordDirectory;
use PhpParser\Node\Stmt\Return_;

class MedCodeVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = 10;
        if(request()->get('length')){
            $length = $request->get('length');
        }
        $medCodeVersions = MedCodeVersion::query();
        if (request()->has('medical_condition') && request()->get('medical_condition')) {
            $medCodeVersions->where('medical_id', request()->get('medical_condition'));
        }  
        $search_keywords = explode(' ', request('search'));
        if (request()->has('search')) {
            if (request()->has('search_type') && request('search_type') == 'exact') {
                $medCodeVersions->where(function ($query) {
                    $query->where('code_version', request('search'))
                        ->orWhere('hcc', request('search'))
                        ->orWhere('id', request('search'))
                        ->orWhere('gender', request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'end') {
                $medCodeVersions->where(function ($query) {
                    $query->where('code_version', 'like', "%" . request('search'))
                        ->orWhere('hcc', 'like', "%" . request('search'))
                        ->orWhere('gender', 'like', "%" . request('search'))
                        ->orWhere('id', 'like', "%" . request('search'));
                });
            } elseif (request()->has('search_type') && request('search_type') == 'begin') {
                $medCodeVersions->where(function ($query) {
                    $query->where('code_version', 'like', request('search') . '%')
                        ->orWhere('gender', 'like', request('search') . '%')
                        ->orWhere('id', 'like', request('search') . '%')
                        ->orWhere('hcc', 'like', request('search') . '%');
                });
            } else {
                if (count($search_keywords) == 1) {
                    $keyword = $search_keywords[0];
                    $medCodeVersions->where(function ($query) use ($keyword) {
                        $query->where('code_version', 'like', '%' . $keyword . '%')
                            ->orWhere('gender', 'like', '%' . $keyword . '%')
                            ->orWhere('id', 'like', '%' . $keyword . '%')
                            ->orWhere('hcc', 'like', '%' . $keyword . '%');
                    });
                } else {

                    foreach ($search_keywords as $index => $term) {
                        if ($index == 0) {
                            $medCodeVersions->where('code_version', 'LIKE', "%" . $term . "%")
                                ->orWhere('gender', 'LIKE', "%" . $term . "%");
                        } else {
                            $medCodeVersions->orWhere('code_version', 'LIKE', "%" . $term . "%");
                            $medCodeVersions->orWhere('gender', 'LIKE', "%" . $term . "%");
                            $medCodeVersions->orWhere('id', 'LIKE', "%" . $term);
                            $medCodeVersions->orWhere('hcc', 'LIKE', $term . "%");
                        }
                    }
                }
            }
        }
       
        $medical_condition = MedicalCondition::where('id', request()->get('medical_condition'))->first();
        $medCodeVersions = $medCodeVersions->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.medical-code-version.load', ['medCodeVersions' => $medCodeVersions])->render();  
        }
        return view('admin.medical-code-version.index',compact('medCodeVersions','medical_condition'));

    }

    public function print(Request $request){
        // return $request->all();
        $medCodeVersions_arr = collect($request->records['data'])->pluck('id');
        $medCodeVersions = MedCodeVersion::whereIn('id', $medCodeVersions_arr)->get();
            return view('admin.medical-code-version.print', ['medCodeVersions' => $medCodeVersions])->render();  
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
        
     $medCodeVersion = MedCodeVersion::find($id);
       return view('admin.medical-code-version.show', compact('medCodeVersions'));
    }

    public function create()
    {
        // return's';
        return view('admin.medical-code-version.create');
    }

    public function store(Request $request)
    {
        $medCodeVersions = MedCodeVersion::create([
           
             'code_version' => $request->code_version,
             'details' => $request->classification,
         
             'medical_id' => $request->medical_id,
             'project_id' => $request->project_id,
             'gender' => $request->gender,
             'hcc' => [
                'rx' => $request->get('hcc-rx'),
                'cms' => $request->get('hcc-cms'),
                'esrd' => $request->get('hcc-esrd'),
            ],
        ]);
        return $this->redirectSuccess(route("admin.medical-code-versions.index",['medical_condition' =>$medCodeVersions->medical_id]), 'Medical code version record added!');
    }

    public function edit( $id)
    {

        $medCodeVersion = MedCodeVersion::find($id);
        return view('admin.medical-code-version.edit', compact('medCodeVersion'));
    }


    public function update(Request $request,$id)
    {
       
        $medCodeVersion = MedCodeVersion::find($id);
        $medCodeVersion->update([
            
             'code_version' => $request->get('code_version'),
             'details' => $request->classification,
            //  'current_year' => $request->current_year,
             'medical_id' => $request->medical_id,
             'project_id' => $request->project_id,
             'gender' => $request->get('gender'),
             'hcc' => [
                'rx' => $request->get('hcc-rx'),
                'cms' => $request->get('hcc-cms'),
                'esrd' => $request->get('hcc-esrd'),
            ],
        ]);
        return $this->redirectSuccess(route("admin.medical-code-versions.index",['medical_condition' =>$medCodeVersion->medical_id]), 'Medical code version Updated!');
    }


    public function destroy($id)
    {
        $medCodeVersion = MedCodeVersion::find($id);
        $medCodeVersion->delete();
        return $this->redirectSuccess(route("admin.medical-code-versions.index",['medical_condition' =>$medCodeVersion->medical_id]), 'Medical code version Deleted!');
    }
    public function bulkAction(Request $request, MedCodeVersion $MedCodeVersion)
    {
        $ids = explode(',', $request->ids);
        foreach($ids as $id) {
            if($id != null){
                MedCodeVersion::where('id', $id)->delete();
            }
        }
        if($ids == [""]){
            return back()->with('error', 'There were no rows selected by you!');
        }else{
            return back()->with('success', 'Medical code version Deleted Successfully!');
        }
    }

    public function clearBulkAction(Request $request)
    {
        try{
            if($request->final_quote == 'delete permanently'){
                MedCodeVersion::whereNotNull('id')->delete();
                return back()->with('success', 'All Record Deleted Successfully!');
            }else{
                return back()->with('error', 'Incorrect input. Please type "delete permanently" to confirm!');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function bulkUpload(Request $request)
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
        $codeVersionIndex = 0;
        $RXIndex = 1;
        $CMSIndex = 2;
        $ESRDIndex = 3;
        $ClassificationIndex = 4;
        $GenderIndex = 5;
        // return dd($GenderIndex);
    
        
        $medical_obj = null;
        // return $request->all();
        foreach ($master as $index => $item) {
            // return dd($item);
            $row_number = $index + 1;
            // $title = MedCodeVersion::where($item[$codeVersionIndex])->first();
            
            if($item[$codeVersionIndex] == null){
                return back()->with("error","Code Version Field is missing at Row:".$row_number." Please export again!");
            }
            // return dd($item[$GenderIndex]);
            if($item[$GenderIndex] != null && $item[$GenderIndex] != 'male' && $item[$GenderIndex] != 'female' && $item[$GenderIndex] != 'both'){
                return back()->with("error","Gender Field is only accepted male , female or both value at Row:".$row_number." Please export again!");
            }

            if((trim($item[$codeVersionIndex]) != null || trim($item[$codeVersionIndex]) != '')  && $item[$codeVersionIndex] != null ){
                $existingRecord = MedCodeVersion::where('code_version', $item[$codeVersionIndex])
                ->exists();
                // if($existingRecord){
                //     return back()->with('error', 'There is already a record with this title, at Row:'.$row_number.'  it should be unique!');
                // }
                // @dd($medical_obj);
                
                
                  $medical_obj = MedCodeVersion::create([ 
                    'hcc' => [
                        'rx' => $item[$RXIndex],
                        'cms' => $item[$CMSIndex],
                        'esrd' => $item[$ESRDIndex],
                    ],
                    'details' => $item[$ClassificationIndex] ?? null,
                    'medical_id' => $request->medical_id ?? null,
                    // 'details' => json_encode($details),
                    'code_version' => $item[$codeVersionIndex],
                    // 'current_year' => $item[$currentYearIndex],
                    'gender' => $item[$GenderIndex],
                ]);
                
            }
        }

        return back()->with('success','Record Created Successfully!');
    }

}


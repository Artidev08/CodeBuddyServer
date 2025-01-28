<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\SupportDiagnosis;
use App\Models\CodeBookmark;

class SupportDiagnosisController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
         $length = 10;
         if(request()->get('length')){
             $length = $request->get('length');
         }
         $support_diagnosiss = SupportDiagnosis::query();
         
            
            if(request()->has('search')){
                if(request()->has('search_type') && request('search_type') == 'exact'){
                    $support_diagnosiss->where(function ($query) {
                        $query->where('dx', request('search'))
                        ->orWhere('description', request('search'))
                        ->orWhere('medication', request('search'));
                    });
                } elseif(request()->has('search_type') && request('search_type') == 'end'){
                    $support_diagnosiss->where(function ($query) {
                        $query->where('dx','like', "%" . request('search'))
                        ->orWhere('description','like', "%" . request('search'))
                        ->orWhere('medication','like', "%" . request('search'));
                    });
                }
                elseif(request()->has('search_type') && request('search_type') == 'begin'){
                    $support_diagnosiss->where(function ($query) {
                        $query->where('dx', 'like', request('search') . '%')
                        ->orWhere('description', 'like', request('search') . '%')
                        ->orWhere('medication', 'like', request('search') . '%');
                    });
                }else{
                    if($request->get('search')){
                        $support_diagnosiss->where('id','like','%'.$request->search.'%')
                                        ->orWhere('dx','like','%'.$request->search.'%')
                                        ->orWhere('description','like','%'.$request->search.'%')
                                        ->orWhere('medication','like','%'.$request->search.'%')
                        ;
                    }
                }
            }
            
            if($request->get('from') && $request->get('to')) {
                $support_diagnosiss->whereBetween('created_at', [\Carbon\carbon::parse($request->from)->format('Y-m-d'),\Carbon\Carbon::parse($request->to)->format('Y-m-d')]);
            }

            if($request->get('asc')){
                $support_diagnosiss->orderBy($request->get('asc'),'asc');
            }
            if($request->get('desc')){
                $support_diagnosiss->orderBy($request->get('desc'),'desc');
            }
            $support_diagnosiss = $support_diagnosiss->latest()->paginate($length);

            if ($request->ajax()) {
                return view('admin.support_diagnosis.load', ['support_diagnosiss' => $support_diagnosiss])->render();  
            }
 
        return view('admin.support_diagnosis.index', compact('support_diagnosiss'));
    }

    
        public function print(Request $request){
            $support_diagnosiss_arr = collect($request->records['data'])->pluck('id');
            $support_diagnosiss = SupportDiagnosis::whereIn('id', $support_diagnosiss_arr)->get();
                return view('admin.support_diagnosis.print', ['support_diagnosiss' => $support_diagnosiss])->render();  
           
        }

       

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view('admin.support_diagnosis.create');
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
                        'dx'     => 'required',
                        'description'     => 'required',
                        'medication'     => 'required',
                    ]);
        
        try{
            $support_diagnosis = SupportDiagnosis::create($request->all());
                return redirect()->route('admin.support-diagnosis.index')->with('success','Support Diagnosis Created Successfully!');
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show(SupportDiagnosis $support_diagnosis)
    {
        try{
            return view('admin.support-diagnosis.show',compact('support_diagnosis'));
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit( $id)
    {   
        try{
            $support_diagnosis = SupportDiagnosis::find($id);
            
            return view('admin.support_diagnosis.edit',compact('support_diagnosis'));
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request,SupportDiagnosis $support_diagnosis,$id)
    {
        $support_diagnosis = SupportDiagnosis::find($id);
        $support_diagnosis->dx = $request->dx;
        $support_diagnosis->description = $request->description;
        $support_diagnosis->medication = $request->medication;
        $this->validate($request, [
                        'dx'     => 'required',
                        'description'     => 'required',
                        'medication'     => 'required',
                    ]);
                
        try{
                           
            if($support_diagnosis){
                       
                $chk = $support_diagnosis->update($request->all());

                return redirect()->route('admin.support-diagnosis.index')->with('success','Record Updated!');
            }
            return back()->with('error','Support Diagnosis not found')->withInput($request->all());
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            if($id){
                $support_diagnosis = SupportDiagnosis::find($id);
                $support_diagnosis->delete();
                return back()->with('success','Supporting Diagnosis deleted successfully');
            }else{
                return back()->with('error','Supporting Diagnosis not found');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request, SupportDiagnosis $support_diagnosis)
    {
        // return $request->all();
        try{
            $ids = explode(',',$request->ids);
            foreach($ids as $id) {
                if($id != null){
                    // CodeBookmark::where('type','Supporting Diagnosis')->where('type_id',$id)->delete();
                    SupportDiagnosis::where('id', $id)->delete();
                }
            }
            if($ids == [""]){
                return back()->with('error', 'There were no rows selected by you!');
            }else{
                return back()->with('success', 'Support Diagnosis Deleted Successfully!');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function clearBulkAction(Request $request, SupportDiagnosis $support_diagnosis)
    {
        try{
            if($request->final_quote == 'delete permanently'){
                // CodeBookmark::whereType('Supporting Diagnosis')->delete();
                SupportDiagnosis::whereNotNull('id')->delete();
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
        $DxIndex = 0;
        $descriptionIndex = 1;
        $MedicationIndex = 2;
       
        $support_diagnosis_obj = null;
        
        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;

            if((trim($item[$DxIndex]) != null || trim($item[$DxIndex]) != '') ){
                $support_diagnosis_obj = SupportDiagnosis::create([ 
                    'dx' => $item[$DxIndex],
                    'description' => $item[$descriptionIndex],
                    'medication' => $item[$MedicationIndex],
                    
                ]);
                
            }
        }

        return back()->with('success','Record Created Successfully!');
    }

}

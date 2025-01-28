<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Exclude;
use App\Models\Project;

class ExcludeController extends Controller
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
         $excludes = Exclude::query();
         if (request()->has('project_id') && request()->get('project_id')) {
            $excludes->where('project_id', request()->get('project_id'));
        }
            
            if(request()->has('search')){
                if(request()->has('search_type') && request('search_type') == 'exact'){
                    $excludes->where(function ($query) {
                        $query->where('from_code', request('search'))
                        ->orWhere('to_code', request('search'))
                        ->orWhere('description', request('search'));
                       
                    });
                } elseif(request()->has('search_type') && request('search_type') == 'end'){
                    $excludes->where(function ($query) {
                        $query->where('from_code','like', "%" . request('search'))
                        ->orWhere('to_code','like', "%" . request('search'))
                        ->orWhere('description','like', "%". request('search'));
                       
                    });
                }
                elseif(request()->has('search_type') && request('search_type') == 'begin'){
                    $excludes->where(function ($query) {
                        $query->where('from_code','like', "%" . request('search'))
                        ->orWhere('to_code','like', "%" . request('search'))
                        ->orWhere('description','like', "%". request('search'));
                      
                    });
                }else{
                    if($request->get('search')){
                        $excludes->where('id','like','%'.$request->search.'%')
                                        ->orWhere('from_code','like','%'.$request->search.'%')
                                        ->orWhere('to_code','like','%'.$request->search.'%')
                                        ->orWhere('description','like','%'.$request->search.'%')
                                       
                        ;
                    }
                }
            }
            
            if($request->get('from') && $request->get('to')) {
                $excludes->whereBetween('created_at', [\Carbon\carbon::parse($request->from)->format('Y-m-d'),\Carbon\Carbon::parse($request->to)->format('Y-m-d')]);
            }

            if($request->get('asc')){
                $excludes->orderBy($request->get('asc'),'asc');
            }
            if($request->get('desc')){
                $excludes->orderBy($request->get('desc'),'desc');
            }
            $projectName = Project::where('id', request()->get('project_id'))->first();
            $excludes = $excludes->latest()->paginate($length);

            if ($request->ajax()) {
                return view('admin.excludes.load', ['excludes' => $excludes])->render();  
            }
 
        return view('admin.excludes.index', compact('excludes','projectName'));
    }

        public function print(Request $request){
            
            $excludes_arr = collect($request->records['data'])->pluck('id');
            $excludes = Exclude::whereIn('id', $excludes_arr)->get();
                return view('admin.excludes.print', ['excludes' => $excludes])->render();  
           
        }
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view('admin.excludes.create');
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
                        'to_code'     => 'required',
                        'from_code'     => 'required',
                        'description'     => 'required',
                       
                    ]);
        
        try{
            $exclude = Exclude::create($request->all());
                return redirect()->route('admin.excludes.index',['project_id'=> $request->project_id])->with('success','Exclude Created Successfully!');
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
    public function show(Exclude $exclude)
    {
        try{
            return view('admin.excludes.show',compact('excludes'));
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
    public function edit(Exclude $exclude)
    {   
        try{
            // return $exclude;
            
            return view('admin.excludes.edit',compact('exclude'));
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
    public function update(Request $request,Exclude $exclude)
    {
        
        $this->validate($request, [
            'to_code'    => 'required',
            'from_code'    => 'required',
            'description'  => 'required',
                       
                    ]);
                
        try{
                           
            if($exclude){
                       
                $chk = $exclude->update($request->all());
                return redirect()->route('admin.excludes.index',['project_id'=> $request->project_id])->with('success','Record Updated!');
            }
            return back()->with('error','Exclude not found')->withInput($request->all());
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
    public function destroy(Exclude $exclude)
    {
        try{
            if($exclude){
                // CodeBookmark::where('type','Supporting Diagnosis')->where('type_id',$exclude->id)->delete();             
                $exclude->delete();
                return back()->with('success','Excludes deleted successfully');
            }else{
                return back()->with('error','Excludes not found');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(Request $request, Exclude $exclude)
    {
        // return $request->all();
        try{
            $ids = explode(',',$request->ids);
            foreach($ids as $id) {
                if($id != null){
                    // CodeBookmark::where('type','Supporting Diagnosis')->where('type_id',$id)->delete();
                    Exclude::where('id', $id)->delete();
                }
            }
            if($ids == [""]){
                return back()->with('error', 'There were no rows selected by you!');
            }else{
                return back()->with('success', 'Exclude Deleted Successfully!');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function clearBulkAction(Request $request, Exclude $exclude)
    {
        try{
            if($request->final_quote == 'delete permanently'){
                // CodeBookmark::whereType('Supporting Diagnosis')->delete();
                Exclude::whereNotNull('id')->delete();
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
        $From_Code = 0;
        $To_Code = 1;
        $Description = 2;
    
       
        $exclude_obj = null;
        
        // return $request->all();
        foreach ($master as $index => $item) {
            $row_number = $index + 1;

            if((trim($item[$From_Code]) != null || trim($item[$From_Code]) != '') ){
                $exclude_obj = Exclude::create([ 
                    'project_id' => $request->project_id?? null,
                    'from_code' => $item[$From_Code],
                    'to_code' => $item[$To_Code],
                    'description' => $item[$Description],
                    
                    
                ]);
                
            }
        }

        return back()->with('success','Record Created Successfully!');
    }
}

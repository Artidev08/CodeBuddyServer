<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\EncounterRequest;
use App\Models\Encounter;
use App\Models\Folder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MailSmsTemplate;
class EncounterController extends Controller
{
    
    protected $viewPath; 
    protected $routePath; 
    public function __construct(){
        $this->viewPath = 'admin.encounters.';
        $this->routePath = 'admin.encounters.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(EncounterRequest $request)
     {
         $length = 10;
         if(request()->get('length')){
             $length = $request->get('length');
         }
         $encounters = Encounter::query();
         
            if($request->get('search')){
                $encounters->where('id','like','%'.$request->search.'%')
                                  
                ->orWhere('payload','like','%'.$request->search.'%')
                                 
                ->orWhere('created_by','like','%'.$request->search.'%')
               ;
            }
            
            if($request->get('from') && $request->get('to')) {
                $encounters->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }

            if($request->get('asc')){
                $encounters->orderBy($request->get('asc'),'asc');
            }
            if($request->get('folder_id')){
                $encounters->where('folder_id',$request->get('folder_id'));
            }
            if($request->get('desc')){
                $encounters->orderBy($request->get('desc'),'desc');
            }
            if($request->get('trash') == 1){
                $encounters->onlyTrashed();
            }
            $folder = Folder::find($request->get('folder_id'));
            $encounters = $encounters->paginate($length);
            $prompt = null;
            $promptVariables = null;
            if($folder){
                $prompt = MailSmsTemplate::where('id',$folder->prompt_id)->first();
                $promptVariables = explode(',',$prompt->variables[0]);
            }
            $bulkActivation = Encounter::BULK_ACTIVATION;
            if ($request->ajax()) {
                return view($this->viewPath.'load', ['encounters' => $encounters,'folder' => $folder ,'promptVariables'=>$promptVariables ,'bulkActivation'=>$bulkActivation])->render();  
            }
 
        return view($this->viewPath.'index', compact('encounters','bulkActivation','folder','promptVariables'));
    }

    public function print(Request $request){
        $encounters = collect($request->records['data']);
        return view($this->viewPath.'print', ['encounters' => $encounters])->render();   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view($this->viewPath.'create');
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EncounterRequest $request)
    {
        try{       
            $encounter = Encounter::create($request->all());
       
                          
            if ($request->hasFile('file')) {
                $fileAdders = $encounter->addMultipleMediaFromRequest(['file'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('file');
                });
            }                          
            if($request->ajax())
                return response()->json([
                    'id'=> $encounter->id,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record Created Successfully!'
                ]);
            else         
            return redirect()->route($this->routePath.'index')->with('success','Encounter Created Successfully!');
        }catch(Exception $e){            
            $bug = $e->getMessage();
            if(request()->ajax())
                return  response()->json([$bug]);
            else
                return redirect()->back()->with('error', $bug)->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Encounter $encounter)
    {
        try{
            return view($this->viewPath.'show',compact('encounter'));
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        try{
            $encounter = Encounter::find($id);
            return view($this->viewPath.'edit',compact('encounter'));
        }catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EncounterRequest $request,Encounter $encounter)
    {
        try{               
            if($encounter){
                       
                $chk = $encounter->update($request->all());
                                 
                if ($request->hasFile('file')) {
                    $fileAdders = $encounter->addMultipleMediaFromRequest(['file'])->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('file');
                    });
                }  
                if($request->ajax())
                    return response()->json([
                        'id'=> $encounter->id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Updated Successfully!'
                    ]);
                else         
                return redirect()->route($this->routePath.'index')->with('success','Record Updated!');
            }
            return back()->with('error','Encounter not found')->withInput($request->all());
        }catch(Exception $e){            
            $bug = $e->getMessage();
            if(request()->ajax())
            return  response()->json([$bug]);
            else
            return redirect()->back()->with('error', $bug)->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $encounter = Encounter::find($id);
            if($encounter){
                $encounter->delete();
                return back()->with('success','Encounter deleted successfully');
            }else{
                return back()->with('error','Encounter not found');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function restore($id)
    {
        try{
           $encounter = Encounter::withTrashed()->where('id', $id)->first();
            if($encounter){
                $encounter->restore();
                return back()->with('success','Encounter restore successfully');
            }else{
                return back()->with('error','Encounter not found');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function moreAction(EncounterRequest $request)
    {
        if(!$request->has('ids') || count($request->ids) <= 0){
            return response()->json(['error' => "Please select atleast one record."], 401);
        }
        try{
            switch (explode('-',$request->action)[0]) {         ;
    
                case 'Move To Trash':
                    Encounter::whereIn('id', $request->ids)->delete();
                    return response()->json([
                        'message' => 'Records moved to trashed successfully.',
                    ]);
                    break;
    
                case 'Delete Permanently':
                    
                    for ($i=0; $i < count($request->ids); $i++) {
                        $encounter = Encounter::withTrashed()->find($request->ids[$i]);                         
                        if ($encounter->getMedia('image')->count()) {
                            $encounter->clearMediaCollection('image');
                        }                        
                        $encounter->forceDelete();
                    }
                    return response()->json([
                        'message' => 'Records deleted permanently successfully.',
                    ]);
                    break;
    
                case 'Restore':
                    
                    for ($i=0; $i < count($request->ids); $i++) {
                       $encounter = Encounter::withTrashed()->find($request->ids[$i]);
                       $encounter->restore();
                    }
                    return response()->json([
                        'message' => 'Records restored successfully.',
                    ]);
                    break;
    
                case 'Export':

                    return Excel::download(new EncounterExport($request->ids), 'Encounter-'.time().'.csv');
                    return response()->json(['error' => "Sorry! Action not found."], 401);
                    break;
                
                default:
                
                    return response()->json(['error' => "Sorry! Action not found."], 401);
                    break;
            }
        }catch(Exception $e){
            return response()->json(['error' => "Sorry! Action not found."], 401);
        }
    }
        
    
      

}

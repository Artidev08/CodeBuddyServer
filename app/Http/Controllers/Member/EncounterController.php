<?php


namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\EncounterRequest;
use App\Models\Encounter;
use App\Models\Folder;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MailSmsTemplate;
use Illuminate\Support\Facades\Session;

class EncounterController extends Controller
{
    
    protected $viewPath; 
    protected $routePath; 
    public function __construct(){
        $this->viewPath = 'member.encounters.';
        $this->routePath = 'member.encounters.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(EncounterRequest $request,$folder_id)
     {
        // return $folder_id;
        $length = 10;
        if(request()->get('length')){
            $length = $request->get('length');
        }
        $folders = Folder::where('created_by',auth()->id())->latest()->get();
        $folder = Folder::where('id',$folder_id)->first();
        $encounters = Encounter::query()->where('folder_id', $folder->id)->where('created_by',auth()->id());
        if ($request->get('search')) {
            $encounters->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('payload', 'like', '%' . $request->search . '%')
                    ->orWhere('created_by', 'like', '%' . $request->search . '%');
            });
        }
        $encountersCount = $encounters->count();
        $encounters = $encounters->latest()->paginate($length);
        $bulkActivation = Encounter::BULK_ACTIVATION;
        $prompt = MailSmsTemplate::where('id',$folder->prompt_id)->first();
        if(!$prompt){
            return redirect()->route('member.folders.edit',$folder->id)->with('error','Folder have no any Comment please select comment!');
        }
        $userId = auth()->id();
        // Retrieve the extracted content from the session
        $extractedContent = Session::get("extracted_content.$folder->id.$userId");

        $data = $request->content ? $request->content : $extractedContent;
        
        $promptVariables = explode(',',$prompt->variables[0]);
        if ($request->ajax()) {
            return view($this->viewPath.'load', ['encounters' => $encounters,'bulkActivation'=>$bulkActivation,'prompt'=>$prompt,'promptVariables' => $promptVariables])->render();  
        }

        return view($this->viewPath.'index', compact('encounters','bulkActivation','folder','prompt','promptVariables','encountersCount','folders','data'));
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
            $payload = [];
            foreach ($request->all() as $key => $value) {
                if($key != '_token' && $key != 'folder_id' && $key != 'created_by') {
                    $payload[$key] = $value;
                }
            }
            // $payload = [
            //     'encounter_type_text' => $request->encounter_type_text, 
            //     'first_name' => $request->first_name, 
            //     'last_name' => $request->last_name, 
            //     'dob' => $request->dob, 
            //     'age' => $request->age, 
            //     'dos' => $request->dos, 
            //     'page_no' => $request->page_no, 
            //     'hcc' => $request->hcc, 
            //     'medication' => $request->medication, 
            //     'record_type' => $request->record_type, 
            //     'doctor_name' => $request->doctor_name, 
            //     'gender' => $request->gender, 
            // ];  
            $request['payload'] = $payload;
            $request['folder_id'] = $request->folder_id;
            $encounter = Encounter::create($request->all());                        
            if($request->ajax())
                return response()->json([
                    'id'=> $encounter->id,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record Created Successfully!'
                ]);
            else         
            return redirect()->route($this->routePath.'index',$request->folder_id)->with('success','Encounter Created Successfully!');
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
    public function edit(Encounter $encounter)
    {   
        try{
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
                // $payload = [
                //     'encounter_type_text' => $request->encounter_type_text, 
                //     'first_name' => $request->first_name, 
                //     'last_name' => $request->last_name, 
                //     'dob' => $request->dob, 
                //     'age' => $request->age, 
                //     'dos' => $request->dos, 
                //     'page_no' => $request->page_no, 
                //     'hcc' => $request->hcc, 
                //     'medication' => $request->medication, 
                //     'record_type' => $request->record_type, 
                //     'doctor_name' => $request->doctor_name, 
                //     'gender' => $request->gender, 
                // ];  
                $payload = [];
                foreach ($request->all() as $key => $value) {
                    if($key != '_token' && $key != 'folder_id' && $key != 'created_by') {
                        $payload[$key] = $value;
                    }
                }
                $request['payload'] = $payload;    
                $chk = $encounter->update($request->all());
                if($request->ajax())
                    return response()->json([
                        'id'=> $encounter->id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Updated Successfully!'
                    ]);
                else         
                return redirect()->route($this->routePath.'index',$encounter->folder_id)->with('success','Record Updated!');
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
    public function destroy(Encounter $encounter)
    {
        try{
            if($encounter){
                $encounter->delete();
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message'=> 'Encounter deleted successfully'
                        ]
                    );
                }
                return back()->with('success','Encounter deleted successfully');
            }else{
                return back()->with('error','Encounter not found');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function ajaxModal(Request $request)
    {
        try{
            $encounter = Encounter::where('id',$request->id)->first();
            $folder = Folder::find($request->folder_id);
            $prompt = MailSmsTemplate::where('id',$folder->prompt_id)->first();
            $promptVariables = explode(',',$prompt->variables[0]);
            return response()->json(['view'=>view('member.encounters.modal',['encounter'=>$encounter,'promptVariables' => $promptVariables])->render(),'folder_id'=>$request->folder_id]);
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
    public function bulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                        'status'=>'Error',
                        'message'=>'Hands Up!","Atleast one row should be selected'
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    Encounter::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                default:
                    $type = "error";
                    $title = 'No action selected!';
            }
            
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'column'=>$request->column,
                        'action'=>$request->action,
                        'data' => $request->ids,
                        'title' => $title,
                        'html' => $html,
    
                    ]
                );
            }
        
            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', 'There was an error: ' . $th->getMessage());
        }
    }


    public function saveExtractedContent(Request $request)
    {
        $extractedContent = $request->input('extractedContent');
        $folderId = $request->input('folderId');
        $userId = auth()->user()->id;

        // Store the extracted content in the session based on folder_id and user_id
        Session::put("extracted_content.$folderId.$userId", $extractedContent);

        return response()->json(['message' => 'Extracted content saved successfully']);
    }
}

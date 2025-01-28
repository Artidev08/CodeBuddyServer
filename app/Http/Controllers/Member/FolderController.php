<?php


namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\FolderRequest;
use App\Models\Folder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Encounter;
use App\Models\MailSmsTemplate;
use App\Models\User;

class FolderController extends Controller
{
    
    protected $viewPath; 
    protected $routePath; 
    public function __construct(){
        $this->viewPath = 'member.folders.';
        $this->routePath = 'member.folders.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(FolderRequest $request)
     {
         $length = 10;
         if(request()->get('length')){
             $length = $request->get('length');
         }
         $folders = Folder::query();
         
            if($request->get('search')){
                $folders->where('id','like','%'.$request->search.'%')
                                  
                ->orWhere('titile','like','%'.$request->search.'%')
                                 
                ->orWhere('created_by','like','%'.$request->search.'%')
               ;
            }
            
            if($request->get('from') && $request->get('to')) {
                $folders->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }

            if($request->get('asc')){
                $folders->orderBy($request->get('asc'),'asc');
            }
            if($request->get('desc')){
                $folders->orderBy($request->get('desc'),'desc');
            }
            if($request->get('trash') == 1){
                $folders->onlyTrashed();
            }
            if($request->has('status') && $request->get('status') != null){
                $folders->where('status',$request->get('status'));
            }
            if($request->get('category')){
                $folders->where('category',$request->get('category'));
            }
            $categories = getCategoriesByCode('FolderCategory');
            $folders = $folders->where('created_by',auth()->id())->paginate($length);
            $bulkActivation = Folder::BULK_ACTIVATION;
            if ($request->ajax()) {
                return view($this->viewPath.'load', ['folders' => $folders,'bulkActivation'=>$bulkActivation])->render();  
            }

          
        return view($this->viewPath.'index', compact('folders','bulkActivation','categories'));
    }

    public function print(Request $request){
        $folders = collect($request->records['data']);
        return view($this->viewPath.'print', ['folders' => $folders])->render();   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $categories = getCategoriesByCode('FolderCategory');
            return view($this->viewPath.'create',compact('categories'));
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
    public function store(FolderRequest $request)
    {
        try{    
            // return $request->all();
            if($request->get('prompt_title') != null){
                $mailSmsTemplate = new MailSmsTemplate();
                $mailSmsTemplate->title=$request->prompt_title;
                $mailSmsTemplate->body=$request->body;
                $mailSmsTemplate->variables=$request->variables;
                $mailSmsTemplate->type=4;
                $mailSmsTemplate->user_id=auth()->id();
                $mailSmsTemplate->save();
                $request['prompt_id']=$mailSmsTemplate->id;
            }
            $folder = Folder::create($request->all());                 
            if($request->ajax())
                return response()->json([
                    'id'=> $folder->id,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record Created Successfully!'
                ]);
            else         
            return redirect()->route('member.dashboard.index')->with('success','Folder Created Successfully!');
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
    public function show(Request $request,Folder $folder)
    {
        try{
            $length = 10;
            if(request()->get('length')){
                $length = $request->get('length');
            }
            $encounters = Encounter::query();
            $encounters->where('folder_id', $folder->id);
            
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
               if($request->get('desc')){
                   $encounters->orderBy($request->get('desc'),'desc');
               }
               if($request->get('trash') == 1){
                   $encounters->onlyTrashed();
               }
               $encounters = $encounters->where('created_by',auth()->id())->latest()->paginate($length);
               $bulkActivation = Encounter::BULK_ACTIVATION;
               if ($request->ajax()) {
                   return view($this->viewPath.'load', ['encounters' => $encounters,'bulkActivation'=>$bulkActivation])->render();  
               }
            return view($this->viewPath.'show',compact('encounters','bulkActivation','folder'));
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
    public function edit(Folder $folder)
    {   
        try{
            $adminIds = User::whereRoleIs('admin')->pluck('id')->toArray();
            $prompts = MailSmsTemplate::whereIn('user_id',$adminIds)->orWhere('user_id',auth()->id())->latest()->get();
            $categories = getCategoriesByCode('FolderCategory');
            $prompt = MailSmsTemplate::where('id',$folder->prompt_id)->first();
            return view($this->viewPath.'edit',compact('folder','categories','prompts','prompt'));
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
    public function update(FolderRequest $request,Folder $folder)
    {
        try{              
            if($folder){
                      
                $chk = $folder->update($request->all());
                
                if($request->ajax())
                    return response()->json([
                        'id'=> $folder->id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Record Updated Successfully!'
                    ]);
                else         
                return redirect()->route('member.dashboard.index')->with('success','Record Updated!');
            }
            return back()->with('error','Folder not found')->withInput($request->all());
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
    public function destroy(Folder $folder)
    {
        try{
            if($folder){
                                    
                $folder->delete();
                return redirect()->route('member.dashboard.index')->with('success','Folder deleted successfully');
            }else{
                return back()->with('error','Folder not found');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function restore($id)
    {
        try{
           $folder = Folder::withTrashed()->where('id', $id)->first();
            if($folder){
                $folder->restore();
                return back()->with('success','Folder restore successfully');
            }else{
                return back()->with('error','Folder not found');
            }
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function moreAction(FolderRequest $request)
    {
        if(!$request->has('ids') || count($request->ids) <= 0){
            return response()->json(['error' => "Please select atleast one record."], 401);
        }
        try{
            switch (explode('-',$request->action)[0]) {      ;
    
                case 'Move To Trash':
                    Folder::whereIn('id', $request->ids)->delete();
                    return response()->json([
                        'message' => 'Records moved to trashed successfully.',
                    ]);
                    break;
    
                case 'Delete Permanently':
                    
                    for ($i=0; $i < count($request->ids); $i++) {
                        $folder = Folder::withTrashed()->find($request->ids[$i]);                         
                        $folder->forceDelete();
                    }
                    return response()->json([
                        'message' => 'Records deleted permanently successfully.',
                    ]);
                    break;
    
                case 'Restore':
                    
                    for ($i=0; $i < count($request->ids); $i++) {
                       $folder = Folder::withTrashed()->find($request->ids[$i]);
                       $folder->restore();
                    }
                    return response()->json([
                        'message' => 'Records restored successfully.',
                    ]);
                    break;
    
                case 'Export':

                    return Excel::download(new FolderExport($request->ids), 'Folder-'.time().'.csv');
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
      
    public function ajaxModal(Request $request)
    {
        try{
            $categories = getCategoriesByCode('FolderCategory');
            $folder = Folder::where('id',$request->id)->first();
            $adminIds = User::whereRoleIs('admin')->pluck('id')->toArray();
            $prompts = MailSmsTemplate::whereIn('user_id',$adminIds)->orWhere('user_id',auth()->id())->latest()->get();
          return response()->json(['view'=>view('member.folders.includes.create-modal',['folder'=>$folder,'categories'=>$categories,'prompts'=>$prompts])->render()]);
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

}

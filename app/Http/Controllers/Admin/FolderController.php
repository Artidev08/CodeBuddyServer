<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\FolderRequest;
use App\Models\Folder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MailSmsTemplate;
use App\Models\User;

class FolderController extends Controller
{
    
    protected $viewPath; 
    protected $routePath; 
    public function __construct(){
        $this->viewPath = 'admin.folders.';
        $this->routePath = 'admin.folders.';
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
                                  
                ->orWhere('title','like','%'.$request->search.'%')
                                 
                ->orWhere('created_by','like','%'.$request->search.'%')
               ;
            }
            
            if($request->get('from') && $request->get('to')) {
                $folders->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }

            if($request->get('asc')){
                $folders->orderBy($request->get('asc'),'asc');
            }
            if($request->has('status') && $request->get('status') != null){
                $folders->where('status',$request->get('status'));
            }
            if($request->get('category')){
                $folders->where('category',$request->get('category'));
            }
            if($request->get('desc')){
                $folders->orderBy($request->get('desc'),'desc');
            }
            if($request->get('trash') == 1){
                $folders->onlyTrashed();
            }
            $categories = getCategoriesByCode('FolderCategory');
            $folders = $folders->latest()->paginate($length);
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
            $adminIds = User::whereRoleIs('admin')->pluck('id')->toArray();
            $prompts = MailSmsTemplate::whereIn('user_id',$adminIds)->orWhere('user_id',auth()->id())->get();
            $categories = getCategoriesByCode('FolderCategory');
            return view($this->viewPath.'create',compact('categories','prompts'));
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
            $folder = Folder::create($request->all());                       
            if($request->ajax())
                return response()->json([
                    'id'=> $folder->id,
                    'status'=>'success',
                    'message' => 'Success',
                    'title' => 'Record Created Successfully!'
                ]);
            else         
            return redirect()->route($this->routePath.'index')->with('success','Folder Created Successfully!');
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
    public function show(Folder $folder)
    {
        try{
            return view($this->viewPath.'show',compact('folder'));
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
            $prompts = MailSmsTemplate::whereIn('user_id',$adminIds)->orWhere('user_id',auth()->id())->get();
            $categories = getCategoriesByCode('FolderCategory');
            return view($this->viewPath.'edit',compact('folder','categories','prompts'));
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
                return redirect()->route($this->routePath.'index')->with('success','Record Updated!');
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
                return back()->with('success','Folder deleted successfully');
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
      

}

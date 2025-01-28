<?php


namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\FolderRequest;
use App\Models\ProjectEntry;

class ProjectEntryController extends Controller
{
    
    protected $viewPath; 
    protected $routePath; 
    public function __construct(){
        $this->viewPath = 'member.entries.';
        $this->routePath = 'member.entries.';
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
        $entriesGroups = ProjectEntry::where('user_id', auth()->id())->latest()->groupBy('group')->get();
        if($request->has('group') && $request->get('group') != null){
            $projectEntries = ProjectEntry::where('user_id', auth()->id())->where('group', $request->get('group'))->get();
        }else{
            $projectEntries = ProjectEntry::where('id', 0)->get();
        }
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;
        return view($this->viewPath.'index', compact('entriesGroups','viewPath','routePath','projectEntries'));
    }

    public function bulkAction(Request $request)
    {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                        'status'=>'error',
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    foreach($request->ids as $id) {
                        $entity = ProjectEntry::where('id', $id)->first();
                        $entitiesIds = ProjectEntry::where('group', $entity->group)->pluck('id');
                        ProjectEntry::whereIn('id', $entitiesIds)->delete();
                    }
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
       
    }
}

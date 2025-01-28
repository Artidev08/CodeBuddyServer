<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProceededContent;

class ProceededContentController extends Controller
{
    
    protected $viewPath; 
    protected $routePath; 
    public function __construct(){
        $this->viewPath = 'admin.proceeded_content.';
        $this->routePath = 'admin.proceeded_content.';
    }
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
         $proceeded_content = ProceededContent::query();
         
            if($request->get('search')){
                $proceeded_content->where('id','like','%'.$request->search.'%')
                ->orWhere('filtered_content','like','%'.$request->search.'%')
                ->orWhere('processed_content','like','%'.$request->search.'%')
                ->orWhere('user_id','like','%'.$request->search.'%')
               ;
            }
            
            if($request->get('from') && $request->get('to')) {
                $proceeded_content->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
            }

            if($request->get('asc')){
                $proceeded_content->orderBy($request->get('asc'),'asc');
            }
            $proceeded_content = $proceeded_content->latest()->paginate($length);
            
            if ($request->ajax()) {
                return view($this->viewPath.'load', ['proceeded_content' => $proceeded_content])->render();  
            }
 
        return view($this->viewPath.'index', compact('proceeded_content'));
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
            $proceededContent = ProceededContent::find($id);
            if($proceededContent){
                $proceededContent->delete();
                return back()->with('success','Proceeded Content deleted successfully');
            }else{
                return back()->with('error','Proceeded Content not found');
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
                        'status'=>'error',
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    
                    ProceededContent::whereIn('id', $request->ids)->delete();
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

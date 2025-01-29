<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScenarioRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Scenario;
use App\Models\Agent;
use App\Models\CodeRegister;
use App\Models\CodeRegisterFile;
use App\Models\CodeRegisterFileLog;
use App\Traits\ManageGithubProcess;
use App\Models\Project;
use Exception;

class CodeRegisterController extends Controller
{
    use ManageGithubProcess;
    public function __construct()
    {
        $this->title = 'Code Register';
        $this->view = 'panel.admin.code-register';
        $this->route = 'panel.admin.code-register';
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['agentRecord'] = Agent::find($request->agent_id);
        $title = isset($data['agentRecord']) ? $data['agentRecord']->name : '';
        $data['view'] = $this->view;
        $data['route'] = $this->route;
        $length = 15;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $rows = CodeRegister::query();
         
        if ($request->get('search')) {
            $rows->where('id', 'like', '%'.$request->search.'%')
            ->orWhere('title', 'like', '%'.$request->search.'%')
            ;
        }
        if ($request->get('from') && $request->get('to')) {
            $rows->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->get('asc')) {
            $rows->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $rows->orderBy($request->get('desc'), 'desc');
        }
        if ($request->get('agent_id')) {
            $rows->where('agent_id', $request->get('agent_id'));
        }

        if ($request->get('project_id')) {
            $project = Project::find($request->get('project_id'));
            $title = isset($project) ? $project->name : '';
            $rows->where('project_id', $request->get('project_id'));
        }
        $data['title'] = $title.' '.$this->title;

        $data['rows'] = $rows->latest()->paginate($length);

        if ($request->ajax()) {
            return view($this->view.'.load', $data)->render();
        }
 
        return view($this->view.'.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $data['agentRecord'] = Agent::find($request->agent_id);
            $title = isset($data['agentRecord']) ? $data['agentRecord']->name : '';
            $data['title'] = $title.' '.$this->title;
            $data['view'] = $this->view;
            $data['route'] = $this->route;
            $data['agents'] = Agent::select('id', 'name')->latest()->get();
            $data['projects'] = Project::select('id', 'name','handle_type','discovery_type')->latest()->get();
            return view($this->view.'.create.index', $data);
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {
            $codeRegister = CodeRegister::create($request->all());
            
            if (request()->ajax()) {
                return response()->json(
                    [
                        'id'=>$codeRegister->id,
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Code Register created successfully!'
                    ]
                );
            }
            return redirect(route($this->route.'.sync',$codeRegister->id))->with('success', 'Code Register Added successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function sync(Request $request, $id)
    {
        try {
            $data['title'] = $this->title;
            $data['view'] = $this->view;
            $data['route'] = $this->route;
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            
            $data['item'] =CodeRegister::whereId($id)->firstOrFail();
            $project = $data['item']->project;
            $base_path = $data['item']->base_path;
            $sync_type = $request->sync_type ? $request->sync_type : 'controllers';

            if($project && $project->discovery_type == Project::DISCOVERY_TYPE_GITHUB){
                $fileToBeExtracted = null;
                if($sync_type == 'views'){
                    $filePath = '/resources/views';
                    $fileToBeExtracted = 'form.blade.php';
                }else{
                    $filePath = '/app/Http/Controllers';
                }
                $data['files'] = $this->getFileNameAndPath($project,$filePath,$fileToBeExtracted);
            }else{
                if($sync_type == 'views'){
                    $bladePath = '/resources/views';
                    $foldersToBeExtracted = ['create', 'edit'];
                    $data['files'] = syncFiles($base_path,$bladePath,$foldersToBeExtracted);
                }else{
                    $filePath = '/app/Http/Controllers';
                    $data['files'] = syncFiles($base_path,$filePath);
                }
            }
           
            $data['existingFiles'] = CodeRegisterFile::where('code_register_id', $id)
                ->where('sync_type',$sync_type)
                ->get()
                ->map(function ($file) {
                    return $file->file_name . '|' . $file->path;
                })->toArray();
            $data['agents'] = Agent::select('id', 'name')->get();
            $data['sync_type'] = $sync_type;
            $data['project'] = $project;
            return view($this->view.'.sync', $data);
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function syncFiles(Request $request, $id)
    {
        $codeRegister = CodeRegister::find($id);

        try {
            if ($codeRegister) {
                if ($request->has('files')) {
                    $selectedFiles = $request->input('files', []); // Array of "file_name|path"
                    $existingFiles = CodeRegisterFile::where('code_register_id', $codeRegister->id)
                        ->where('sync_type',$request->sync_type)
                        ->get()
                        ->map(function ($file) {
                            return $file->file_name . '|' . rtrim($file->path, '/');
                        })
                        ->toArray();

                    // Determine files to delete (present in DB but not in selectedFiles)
                    $filesToDelete = array_diff($existingFiles, $selectedFiles);
                    if (!empty($filesToDelete)) {
                        foreach ($filesToDelete as $fileToDelete) {
                            [$file_name, $path] = explode('|', $fileToDelete);
                            $cr_files = CodeRegisterFile::where('code_register_id', $codeRegister->id)
                            ->where('file_name', $file_name)
                            ->where('sync_type',$request->sync_type)
                            ->where('path', $path . '/')
                            ->get();
                        
                            // Collect all file IDs to delete related logs in a single query
                            $cr_file_ids = $cr_files->pluck('id');
                            
                            // Delete related logs in a single query
                            CodeRegisterFileLog::where('code_register_id', $codeRegister->id)
                                ->whereIn('code_register_file_id', $cr_file_ids)
                                ->delete();
                            
                            // Delete the files in a single query
                            CodeRegisterFile::whereIn('id', $cr_file_ids)->delete();
                        }
                    }

                    // Determine files to add (present in selectedFiles but not in DB)
                    $filesToAdd = array_diff($selectedFiles, $existingFiles);
                    if (!empty($filesToAdd)) {
                        foreach ($filesToAdd as $fileToAdd) {
                            [$file_name, $path] = explode('|', $fileToAdd);
                            $cr_file = new CodeRegisterFile();
                            $cr_file->code_register_id = $codeRegister->id;
                            $cr_file->path = rtrim($path, '/') . '/';
                            $cr_file->file_name = $file_name;
                            $cr_file->sync_type = $request->sync_type;
                            $cr_file->save();
                        }
                    }
                    if (request()->ajax()) {
                        return response()->json(
                            [
                                'status'=>'success',
                                'message' => 'Success',
                                'title' => 'Code Register syncs successfully!'
                            ]
                        );
                    }
                    return redirect(url('/api/controllers-code-optimization/' . $codeRegister->id . '/progress'))
                        ->with('success', 'Code Register updated successfully.');
                }
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'error',
                            'message' => 'Error',
                            'title' => 'Please select atleast one file!'
                        ]
                    );
                }
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }

    
    public function edit(Request $request, $id)
    {
        try {
            $data['title'] = $this->title;
            $data['view'] = $this->view;
            $data['route'] = $this->route;
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $data['item'] =CodeRegister::whereId($id)->firstOrFail();
            $data['agents'] = Agent::select('id', 'name')->get();
            return view($this->view.'.edit.index', $data);
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $codeRegister = CodeRegister::find($id);

        try {
            if ($codeRegister) {
                $chk = $codeRegister->update($request->all());
                
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Code Register updated successfully!'
                        ]
                    );
                }
            }
            return redirect()->route($this->route.'.index')->with('success', 'Code Register updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $codeRegister =CodeRegister::whereId($id)->firstOrFail();
            if ($codeRegister) {
                $codeRegister->delete();
                return back()->with('success', 'Code Register deleted successfully');
            } else {
                return back()->with('error', 'Code Register not found');
            }
        } catch (Exception $e) {
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
                    CodeRegister::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
    
                // Column Update
                
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
}

<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\CodeRegister;
use App\Models\CodeRegisterAgent;
use App\Models\CodeRegisterAgentLog;
use App\Models\CodeRegisterFile;
use App\Models\CodeRegisterFileLog;
use App\Traits\ManageGithubProcess;
use App\Traits\ManageAgents;
use App\Traits\MergeCodeChanges;
use App\Models\Project;
use Exception;
use ZipArchive;

class CodeRegisterController extends Controller
{
    use ManageGithubProcess, ManageAgents, MergeCodeChanges;
    protected $view;
    protected $route;
    public $title;
    public function __construct()
    {
        $this->title = 'Modules';
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
        $length = 16;
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
            $request['base_path'] = $request['base_path'] ?  $request['base_path'] : '/home/usmlestu/code-buddy.dze-labs-core/';
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

    public function syncOld(Request $request, $id)
    {
        try {
            $data['title'] = $this->title;
            $data['view'] = $this->view;
            $data['route'] = $this->route;
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            
            $data['item'] = CodeRegister::whereId($id)->firstOrFail();
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

            $data['files'] = syncFiles($base_path,$filePath);
           
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

    public function playground($id)
    {
        try{
            if(!is_numeric($id)){
                $id = secureToken($id,'decrypt');
            }
            $data['projectEntity'] = CodeRegister::where('id', $id)->first();
            $data['projectEntities'] = CodeRegister::select('id','module','title')->get();;
            $data['files'] = CodeRegisterFile::where('code_register_id', $id)->orderBy('group')->get();
            return view($this->view.'.playground.index', $data);
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function tasks($id)
    {
        try{
            if(!is_numeric($id)){
                $id = secureToken($id,'decrypt');
            }
            $data['projectEntity'] = CodeRegister::where('id', $id)->first();
            $data['projectEntities'] = CodeRegister::select('id','module','title')->get();
            $data['registerAgents'] = CodeRegisterAgent::where('code_register_id', $id)->latest()->get();
            $data['agents'] = Agent::whereNotIn('id', $data['registerAgents']->pluck('agent_id')->toArray())->latest()->get();
            $data['agentData'] = $data['registerAgents']->first();
            $data['completedLogCount'] = 0;
            $data['totalLogCount'] = 0;
            if($data['agentData']){
                $data['agentLogs'] = CodeRegisterAgentLog::where('code_register_agent_id',$data['agentData']->id)->latest()->get();
                $data['completedLogCount'] = getAgentCompletedLogCount($data['agentData']);
                $data['totalLogCount'] = $data['agentData']->codeRegisterAgentLogs->count();
                $data['statuses'] = $data['agentData']->codeRegisterAgentLogs->pluck('status')->toArray();
            }else{
                $data['agentLogs'] = CodeRegisterAgentLog::where('id',0)->latest()->get();
                $data['statuses'] = null;
            }
            $data['scope_categories'] = getCategoriesByCode('ScopeCategories');
            $data['progressPercentage'] = $data['totalLogCount'] > 0 ? ($data['completedLogCount'] / $data['totalLogCount']) * 100 : 0;

            return view($this->view.'.tasks.index', $data);
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function syncAgents($id)
    {
        try {
            $cr = CodeRegister::find($id);
            if (!$cr) {
                return back()->with('error', 'CodeRegister not found.');
            }
    
            // Get the list of existing agent IDs for this CodeRegister
            $existingAgents = CodeRegisterAgent::where('code_register_id', $id)->pluck('agent_id')->toArray();
    
            // Check if there are any agents linked to this CodeRegister
            if ($cr->codeRegisterAgents->count() > 0) {
                // Check if all tasks are merged
                $isAllMerged = $this->checkIfAllTasksAreMerged($cr);
                if ($isAllMerged == false) {
                    return redirect(route($this->route.'.tasks',[secureToken($cr->id)]))->with('error', 'Not all tasks have been merged or some tasks are still incomplete.');
                }
            }
            // Sync agents with the given CodeRegister
            $this->syncModuleAgents($cr->id, $existingAgents,request()->agent_id);
            // Append agent_id to the URL and redirect back
          
            return redirect(route($this->route.'.tasks',[secureToken($cr->id),'is_run' => true]))->with('success', 'Agents synced successfully.');
        } catch (Exception $e) {
            // Provide more detailed error handling
            return back()->with('error', 'There was an error during the sync process: ' . $e->getMessage());
        }
    }
    

    public function getLogs(Request $request, $id)
    {
        try {
            $projectEntity = CodeRegister::where('id', $id)->first();
            $agentData = CodeRegisterAgent::find($request->agent_id);
    
            if (!$agentData) {
                return response()->json(['error' => 'Agent not found'], 404);
            }
            $registerAgents = CodeRegisterAgent::where('code_register_id', $id)->latest()->get();

            // Query builder with optional status filtering
            $query = CodeRegisterAgentLog::where('code_register_id', $id)
                ->where('code_register_agent_id', $agentData->id);
    
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            $statuses = $agentData->codeRegisterAgentLogs->pluck('status')->toArray();
            $agentLogs = $query->latest()->get();
    
            // Calculate log progress
            $completedLogCount = getAgentCompletedLogCount($agentData);
            $totalLogCount = $agentData->codeRegisterAgentLogs->count();
            $progressPercentage = $totalLogCount > 0 ? ($completedLogCount / $totalLogCount) * 100 : 0;
    
            return response()->json([
                'html' => view("{$this->view}.tasks.includes.file-content", compact(
                    'agentLogs', 'agentData', 'completedLogCount', 'totalLogCount', 'progressPercentage','projectEntity','statuses', 'registerAgents'
                ))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'There was an error: ' . $e->getMessage()], 500);
        }
    }
    

    public function updateStatus(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;

            if(!is_numeric($id)){
                $id = secureToken($id);
            }
            $agentLog = CodeRegisterAgentLog::where('id', $id)
                ->first();

            if($agentLog){
                $agentLog->status = $status;
                $agentLog->save();
            }

            if($request->ajax()){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Status Updated!',
                ]);
            }
            return back()->with('Status Updated!');
        } catch (Exception $e) {
            return response()->json(['error' => 'There was an error: ' . $e->getMessage()], 500);
        }
    }

    public function mergeCode($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $codeRegisterAgent = CodeRegisterAgent::whereId($id)->first();
            if(!$codeRegisterAgent){
                return false;
            }

            $file_count = 0;
            // Sync Changes
            $scopedChangesFiles = $this->syncMergeChanges($codeRegisterAgent);
            // End Sync Changes
            
            // Formatting output as a list
            if (!empty($scopedChangesFiles) && is_array($scopedChangesFiles)) {
                $file_count = count($scopedChangesFiles);
                $fileChangesContent = "Scope:\n";
                $fileChangesContent .= "- " . implode("\n- ", $scopedChangesFiles);
                $codeRegister = $codeRegisterAgent->codeRegister;
                if ($codeRegister->project_id !== null) {
                    $project = $codeRegister->project;
                    return  $this->pushCodeInGit($project);

                    // if($project){
                    //     if ($project->discovery_type == Project::DISCOVERY_TYPE_GITHUB) {
                    //         // Push Changes in Github
                    //         $this->pushCodeInGit($fileChangesContent);
                    //     }else{
                    //         if($project->local_payload != null && isset($project->local_payload['base_path']) && $project->local_payload['base_path'] !== null){
                    //             if($file_count > 0){
                    //                 $local_path = $project->local_payload['base_path'];

                    //                 // Update all cloned files to project folder
                    //                 $clonedFilePath = storage_path("app/public/env/isolate/{$project->id}/{$project->name}");

                    //                 // Define the path for the zip file
                    //                 $zipFilePath = storage_path("app/public/env/isolate/{$project->id}/{$project->name}.zip");

                    //                 // Create ZIP archive
                    //                 $this->createProjectZip($clonedFilePath, $zipFilePath);

                    //                 // Extract ZIP file
                    //                 $this->extractProjectZip($zipFilePath, $local_path);

                    //                 // Step 3: Delete the ZIP file after extraction
                    //                 $this->deleteProjectZip($zipFilePath);
                    //             }
                    //         }else{
                    //             Log::info("Base Path Not Found in : {$project->name}");
                    //         }
                    //     }

                        // if($project->project_register_id !== null){
                        //     // Storing Task in HQ ERP 
                        //     $headers = [
                        //         'Accept' => 'application/json',
                        //     ];
                        //     $task = "Title: <br>
                        //     Fixes by {$project->name} - RefID ({$project->getPrefix()}) [Fixed {$file_count}Fs] <br>
                        //     Files: \n - " . implode("\n\n- ", $scopedChangesFiles). "<br> 
                        //     Comment: ". nl2br($codeRegisterAgent->output_content);
                        //     $data = [
                        //         'project_register_id' => $project->project_register_id,
                        //         'error_msg' => $task,
                        //         'request_link' => '#'
                        //     ];
                            
                        //     $apiUrl = env('HQ_ERP_URL');
                        //     // Calling HQ api for storing tasks
                        //     $response = $this->postContentByCurl($apiUrl, $data, $headers);
                        //     // End Calling HQ api for storing tasks
                        //     // End Storing Task in HQ ERP
                        // }
                    // }
                }
            }            
            
            return back()->with('success', 'Merged Successfully');
        } catch (Exception $e) {
            return response()->json(['error' => 'There was an error: ' . $e->getMessage()], 500);
        }
    }

    private function createProjectZip($basePath, $zipFilePath)
    {
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Function to add files and folders to the zip
            function addFolderToZip($dir, $zip, $basePath) {
                // Create a recursive directory iterator
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $file) {
                        // Skip directories (they will be added automatically)
                        if (!$file->isDir()) {
                            // Get the real and relative path for current file
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($basePath) + 1);

                            // Add current file to the zip
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }

            // Add the cloned files to the zip
            addFolderToZip($basePath, $zip, $basePath);

            // Close the zip file
            $zip->close();
        }

        return true;
    }

    private function extractProjectZip($zipFilePath, $storagePath)
    {
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath) === true) {
            $zip->extractTo($storagePath);
            $zip->close();
        } else {
            throw new Exception("Failed to extract ZIP file.");
        }
    }

    private function deleteProjectZip($zipFilePath)
    {
        if (file_exists($zipFilePath)) {
            unlink($zipFilePath);
        }
    }
}
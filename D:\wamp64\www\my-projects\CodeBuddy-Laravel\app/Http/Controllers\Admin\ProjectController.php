<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Models\CodeRegister;
use App\Models\CodeRegisterAgent;
use App\Models\CodeRegisterFile;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Traits\ManageModule;
use App\Traits\ManageAgents;
use App\Traits\ManageAgentLogs;

class ProjectController extends Controller
{
    use ManageModule, ManageAgents, ManageAgentLogs;
    protected $viewPath;
    protected $routePath;
    public $label;
    public function __construct()
    {
        $this->viewPath = 'panel.admin.projects.';
        $this->routePath = 'panel.admin.projects.';
        $this->label = 'Projects';
    }
    /** * Display a listing of the resource. *
     * @return \Illuminate\Http\Response */
    public function index(Request $request)
    {
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $projects = Project::query();

        if ($request->get('search')) {
            $projects->where('id', 'like', '%' . $request->search . '%')->orWhere('name', 'like', '%' . $request->search . '%');
        }

        if ($request->get('from') && $request->get('to')) {
            $projects->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d') . '
    00:00:00', \Carbon\Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"]);
        }

        if ($request->get('asc')) {
            $projects->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $projects->orderBy($request->get('desc'), 'desc');
        }
        if ($request->get('trash') == 1) {
            $projects->onlyTrashed();
        }
        $projects = $projects->latest()->paginate($length);
        $label = $this->label;
        $bulkActivation = Project::BULK_ACTIVATION;
        if ($request->ajax()) {
            return view($this->viewPath . 'load', ['projects' =>
            $projects, 'bulkActivation' => $bulkActivation])->render();
        }

        return view($this->viewPath . 'index', compact('projects', 'bulkActivation', 'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view($this->viewPath . 'create');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        try {
            $request['slug'] = Str::slug($request['name']);
            $existSlug = Project::where('slug', $request['slug'])->exists();
            if($existSlug){
                $responseData = [
                    'status' => 'error',
                    'message' => 'Error',
                    'title' => 'Project Already Exist',
                ];
                return responseOrRedirect($request, $responseData);
            }
            $project = Project::create($request->all());
            $dir = null;
            // Define allowed directories
            $allowedDirs = ['app', 'resources', 'routes', 'database/migrations'];
            // If discovery type is 'local', handle local discovery
            if ($project->discovery_type == Project::DISCOVERY_TYPE_LOCAL) {
                $dir = $this->handleLocalDiscovery($project,$allowedDirs);
            }elseif($project->discovery_type == Project::DISCOVERY_TYPE_GITHUB){
                $dir = $this->fetchFilesFromGithub($project,$allowedDirs);
            }
            $project->base_path = $dir;
            $project->save();
            $responseData = [
                'id' => $project->id,
                'status' => 'success',
                'message' => 'success',
                'redirect_route' => redirect()->route($this->routePath . 'index')->with('success', 'Project Created
                Successfully!'),
                'title' => 'Record Created Successfully!',
            ];
            return responseOrRedirect($request, $responseData);
        } catch (Exception $e) {
            $bug = $e->getMessage();
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' => $bug,
            ];
        }
    }

    private function fetchFilesFromGithub($project,$directories = ['app', 'resources', 'routes', 'database/migrations'])
    {
        $owner = $project->github_payload['owner_name'] ?? env('GITHUB_USERNAME');
        $repo = $project->github_payload['project_repo_name'] ?? env('GITHUB_TEST_REPO_NAME');
        $accessToken = $project->github_payload['access_token'] ?? env('GITHUB_PERSONAL_ACCESS_TOKEN');
        
        $githubApiUrl = "https://api.github.com/repos/{$owner}/{$repo}/contents/";
        
        $slug = $project->slug;
        $zip = new ZipArchive();
        $zipFilePath = storage_path("app/public/env/isolate/{$project->id}/{$slug}.zip");

        // $zipFilePath = storage_path('app/projects/' . $slug . '.zip');
        
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Failed to create ZIP file.');
        }
    
        foreach ($directories as $dir) {
            $response = Http::withToken($accessToken)->get($githubApiUrl . $dir);
            if ($response->successful()) {
                $files = $response->json();
    
                foreach ($files as $file) {
                    if ($file['type'] == 'file') {
                        $fileContent = Http::withToken($accessToken)->get($file['download_url'])->body();
                        $zip->addFromString($dir . '/' . $file['name'], $fileContent);
                    } elseif ($file['type'] == 'dir') {
                        $this->addDirectoryToZip($file['path'], $zip, $owner, $repo, $accessToken, $dir);
                    }
                }
            } 
        }
    
        $zip->close();
        $projectName = $project->slug;
        $projectId = $project->id;
        $storagePath = storage_path("app/public/env/isolate/{$projectId}/{$projectName}");
    
        // // Step 2: Extract ZIP file
        $this->extractProjectZip($zipFilePath, $storagePath);

        // // Step 3: Delete the ZIP file after extraction
        $this->deleteProjectZip($zipFilePath);

        return $storagePath;
    }


    // Helper function to recursively add subdirectories to the ZIP
    private function addDirectoryToZip($path, $zip, $owner, $repo, $accessToken, $parentDir)
    {
        $githubApiUrl = "https://api.github.com/repos/{$owner}/{$repo}/contents/{$path}";

        $response = Http::withToken($accessToken)->get($githubApiUrl);

        if ($response->successful()) {
            $files = $response->json();
            foreach ($files as $file) {
                if ($file['type'] == 'file') {
                    // Download the file content
                    $fileContent = Http::withToken($accessToken)->get($file['download_url'])->body();

                    // Add to zip
                    $zip->addFromString("{$parentDir}/{$path}/" . $file['name'], $fileContent);
                    // Debugging: Log the added file
                    \Log::info("Added file from subdir: {$parentDir}/{$path}/" . $file['name']);
                } elseif ($file['type'] == 'dir') {
                    // Recursively add files from subdirectory
                    $this->addDirectoryToZip($file['path'], $zip, $owner, $repo, $accessToken, $parentDir);
                }
            }
        } else {
            \Log::error("Failed to fetch directory: {$path}");
        }
    }
    private function handleLocalDiscovery($project,$allowedDirs)
    {
        $basePath = $project->local_payload['base_path'];
        $projectName = $project->slug;
        $projectId = $project->id;
        $storagePath = storage_path("app/public/env/isolate/{$projectId}/{$projectName}");
    
        if (!file_exists($basePath)) {
            throw new Exception("Base path does not exist.");
        }
    
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
    
        $zipFileName = "{$projectName}.zip";
        $zipFilePath = storage_path("app/public/env/isolate/{$projectId}/{$zipFileName}");
        
        // Step 1: Create ZIP archive
        $this->createProjectZip($basePath, $zipFilePath,$allowedDirs);

        // Step 2: Extract ZIP file
        $this->extractProjectZip($zipFilePath, $storagePath);

        // Step 3: Delete the ZIP file after extraction
        $this->deleteProjectZip($zipFilePath);

        return $storagePath;
    }
    

    private function createProjectZip($basePath, $zipFilePath,$allowedDirs)
    {
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($allowedDirs as $dir) {
                $fullPath = "{$basePath}/{$dir}";
                if (file_exists($fullPath)) {
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($fullPath, \FilesystemIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );
    
                    foreach ($files as $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = "{$dir}/" . substr($filePath, strlen($fullPath));
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }
            }
    
            $zip->close();
        } else {
            throw new Exception("Failed to create ZIP file.");
        }
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
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = decrypt($id);
            }
            $project = Project::where('id', $id)->first();
            return view($this->viewPath . 'show', compact('project'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = decrypt($id);
            }
            $project = Project::where('id', $id)->first();
            return view($this->viewPath . 'edit', compact('project'));
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = decrypt($id);
            }
            $project = Project::where('id', $id)->first();
            if ($project) {

                $chk = $project->update($request->all());

                if ($request->ajax())
                    return response()->json([
                        'id' => $project->id,
                        'status' => 'success',
                        'message' => 'Success',
                        'title' => 'Record Updated Successfully!'
                    ]);
                else
                    return redirect()->route($this->routePath . 'index')->with('success', 'Record Updated!');
            }
            return back()->with('error', 'Project not found')->withInput($request->all());
        } catch (Exception $e) {
            $bug = $e->getMessage();
            if (request()->ajax())
                return response()->json([$bug]);
            else
                return redirect()->back()->with('error', $bug)->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = decrypt($id);
            }
            $project = Project::where('id', $id)->first();
            if ($project) {

                $project->delete();
                return back()->with('success', 'Project deleted successfully');
            } else {
                return back()->with('error', 'Project not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function playground($id)
    {
        try{
            if($id == 'id') {
                $data['projectEntity'] = Project::latest()->first();
                return redirect()->route($this->routePath.'playground',$data['projectEntity']->id)->with('success', 'Record Updated!');
            }
            $data['projectEntity'] = Project::where('id', $id)->first();
            $data['projectEntities'] = Project::select('id','name')->get();;
           
            return view($this->viewPath.'playground.index', $data);
        }catch(Exception $e){
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    
    //Get the content of a specified file.
    public function getFileContent(Request $request)
    {
        // Retrieve directory and file name from query parameters
        $mainDir = $request->query('dir');
        $fileName = $request->query('file');

        if($fileName){
            // Construct the full file path
            $filePath = storage_path("app/public/env/isolate/{$mainDir}/{$fileName}");
        }else{
            $filePath = $mainDir;
        }

        // Check if the file exists and is readable
        if (is_file($filePath) && is_readable($filePath)) {
            // Get the file content
            $fileContent = file_get_contents($filePath);
            $lastModifiedTime = filemtime($filePath);
            $carbonLastModified = Carbon::createFromTimestamp($lastModifiedTime);
            $updated_at = $carbonLastModified->diffForHumans();
            // Return the file content as JSON response
            return response()->json(['content' => $fileContent,'updated_at' => $updated_at]);
        } else {
            // Return an error response if the file is not found or not readable
            return response()->json(['error' => 'File not found or not readable'], 404);
        }
    }

    // Download a specified file or the entire directory as a ZIP file.
    public function downloadFile(Request $request)
    {
        // Retrieve the directory from query parameters
        $mainDir = $request->query('dir');
        $id = $request->query('id');

        if (isset($request->file)) {
            // If a specific file is requested, construct its path
            $fileName = $request->file;
            $filePath = storage_path("app/public/env/isolate/{$id}/{$fileName}");
        } else {
            // If no specific file, create a ZIP of the directory
            $zipFilePath = downloadZipFolder($mainDir);
        }

        if (isset($zipFilePath)) {
            // If ZIP file was created, return it as a download response
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } elseif (isset($filePath)) {
            // If a specific file was requested, return it as a download response
            return response()->download($filePath);
        } else {
            // If neither was found or readable, redirect back with an error message
            $msg = isset($zipFilePath) ? 'Could not create ZIP file' : 'File not found or not readable';
            return redirect()->back()->with('error', $msg);
        }
    }


    //Save or update the content of a specified file.
    public function saveFile(Request $request)
    {
        $path = $request->input('file_path');
        $content = $request->input('file_content');

        // Construct the full file path
        $filePath = $path;

        // Check if the file exists and is writable
        if (is_file($filePath) && is_writable($filePath)) {
            // Update the file content
            file_put_contents($filePath, $content);
            $lastModifiedTime = filemtime($filePath);
            $carbonLastModified = Carbon::createFromTimestamp($lastModifiedTime);
            $updated_at = $carbonLastModified->diffForHumans();
            // Return a success response
            return response()->json(['message' => 'File updated successfully','updated_at' => $updated_at]);
        } else {
            // Return an error response if the file is not found or not writable
            return response()->json(['error' => 'File not found or not writable'], 404);
        }
    }


    public function syncRegister(Request $request, $id)
    {
        try {
            $data['title'] = 'Sync Register';
            $data['view'] = $this->viewPath;
            $data['route'] = $this->routePath;
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            
            $data['project'] = Project::whereId($id)->firstOrFail();
            $base_path = $data['project']->base_path;

            $routesPath = $base_path . '/routes'; // Path to routes directory
            $data['files'] = $this->getAllModules($routesPath);
            return view($this->viewPath.'.sync.index', $data);
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
    public function storeSyncedRegister(Request $request, $id)
    {
        $project = Project::find($id);
        // try {
            if ($project) {
                if ($request->has('files')) {
                    $selectedFiles = $request->input('files', []);

                    // Retrieve existing entries for the project
                    $existingEntries = CodeRegister::where('project_id', $project->id)->get()->keyBy(function ($item) {
                        return $item->title . '|' . $item->module;
                    });
                    // Process selected files
                    foreach ($selectedFiles as $fileKey) {
                        // Get file details from hidden inputs
                        $group = $request->file_group[$fileKey] ?? null;
                        $module = $request->file_module[$fileKey] ?? null;
                        $controllerPath = $request->file_controller_path[$fileKey] ?? '';
                        $code = $request->code[$fileKey] ?? null;
                    
                        $uniqueKey = $group . '|' . $module;
                    
                        // Check if the entry already exists
                        if (!isset($existingEntries[$uniqueKey])) {
                            // Create new entry if it doesn't exist
                            $cr = new CodeRegister();
                            $cr->project_id = $project->id;
                            $cr->title = $group;
                            $cr->module = $module;
                            $cr->code = $code;
                            $cr->base_path = $controllerPath;
                            $cr->project_path = $project->base_path;
                            $cr->save();
                            $this->syncModuleFiles($cr->id);
                            // $this->syncModuleAgents($cr->id);
                        }
                        // Remove from existing entries list to track which ones remain
                        unset($existingEntries[$uniqueKey]);
                    }
                    
                    // Delete unselected entries
                    foreach ($existingEntries as $entry) {
                        CodeRegisterFile::where('code_register_id', $entry->id)->delete();
                        CodeRegisterAgent::where('code_register_id', $entry->id)->delete();
                        $entry->delete();
                    }
                  
                    if (request()->ajax()) {
                        return response()->json(
                            [
                                'project_id'=> $project->id,
                                'status'=>'success',
                                'message' => 'Success',
                                'title' => 'Code Register syncs successfully!'
                            ]
                        );
                    }
                    return redirect(url('/api/controllers-code-optimization/' . $project->id . '/progress'))
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
        // } catch (Exception $e) {
        //     return back()->with('error', 'There was an error: ' . $e->getMessage())->withInput($request->all());
        // }
    }

     /**
     * Get all modules grouped by their respective route files.
     */
    public function getAllModules($path, $response_type = 'module_files'): array
    {
        $modulesByFile = [];
        $files = File::allFiles($path);
        // Loop through route files
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $fileName = $file->getFilename();
            
            // Extract route groups from the file
            $routeGroups = $this->extractRouteGroups($filePath,$fileName);

            // Group by file, then by module
            foreach ($routeGroups as $group) {
                $moduleName = str_replace('/', '', ltrim($group['prefix'])); // Remove slashes
                $groupCode = $group['code'];
                $groupController = $group['controller_path'];

                // Group by file name first
                $modulesByFile[$fileName][$moduleName] = [
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_code' => $groupCode,
                    'controller_path' => $groupController
                ];
            }
        }

        if($response_type == 'file_groups'){
            return array_keys($modulesByFile);
        }

        return $modulesByFile;
    }

     /**
     * Extract route groups from a file.
     */
    public function extractRouteGroups(string $filePath,$fileName): array
    {
        $content = File::get($filePath);
        $routeGroups = [];
        preg_match_all('/Route::group\s*\(\s*\[([^\]]+)\]\s*\,(.*?)\{(.*?)\}\s*\)\s*;/s', $content, $matches, PREG_SET_ORDER);
        // preg_match_all("/Route::group\s*\(\s*\[([^\]]+)\](.*?)\)\s*;/s", $content, $matches, PREG_SET_ORDER);
    
        foreach ($matches as $match) {
            $prefix = $this->extractPrefix($match[1]);
            $groupCode = trim($match[0]); // Full Route::group code
    
            // Extract namespace and controller names
            $namespace = $this->extractNamespace($match[1]);
            $controller = $this->extractController($match[0]);
    
            if(!$namespace){
                if (strpos($controller, 'App\Http') === 0) {
                    $controller = str_replace('App\\Http', 'app\\Http', $controller);
                }else{
                    $namespaceName = ucfirst(str_replace('.php', '', $fileName)); 
                    $namespace = 'app\\Http\\Controllers\\' . $namespaceName;
                }
            }else{
                if (strpos($namespace, 'App\Http') === 0) {
                    $namespace = str_replace('App\\Http', 'app\\Http', $namespace);
                }
            }
            if (!$controller) {
                $controllerName = str_replace('/', '', ltrim($this->pluralToSingular($prefix)));
                $controller = ucfirst(str_replace(['-', '{', '}', '_'], '', $controllerName)) . 'Controller'; 
            }
            // Combine namespace and controller to form a full path
            $controllerFullPath = $namespace ? $namespace . '\\' . $controller : $controller;
    
            if($controllerFullPath){
                if ($prefix) {
                    $routeGroups[] = [
                        'prefix' => $prefix,
                        'namespace' => $namespace,
                        'controller' => $controller,
                        'controller_path' => $controllerFullPath,
                        'code' => $groupCode
                    ];
                }
            }
        }
    
        return $routeGroups;
    }
    
      /**
     * Extract prefix from a route group string.
     */
    private function extractPrefix(string $groupString): ?string
    {
        if (preg_match("/'prefix'\s*=>\s*'([^']+)'/", $groupString, $prefixMatch)) {
            return trim(preg_replace('/\{.*?\}/', '', $prefixMatch[1]), '/');
            // return $prefixMatch[1]; // Extracted module name
        }
        return null;
    }

    /**
     * Extract the namespace from the route group string.
     */
    private function extractNamespace(string $groupString): ?string
    {
        // Match the 'namespace' key and capture the value (considering different quote styles)
        if (preg_match("/'namespace'\s*=>\s*'([^']+)'/", $groupString, $namespaceMatch)) {
            return $namespaceMatch[1]; // Extracted namespace
        }
        
        // Handle case where the namespace is defined with double quotes
        if (preg_match('/"namespace"\s*=>\s*"([^"]+)"/', $groupString, $namespaceMatch)) {
            return $namespaceMatch[1]; // Extracted namespace
        }

        return null;
    }

    /**
     * Extract the controller name from the route group string.
     */
    private function extractController(string $groupString): ?string
    {
        // Check for the 'controller' key (direct controller assignment)
        if (preg_match("/'controller'\s*=>\s*([A-Za-z0-9\\\\_]+)(::class)?/", $groupString, $controllerMatch)) {
            return $controllerMatch[1]; // Extracted controller class name
        }

        if (preg_match("/'uses'\s*=>\s*'([A-Za-z0-9\\\\_]+)@/", $groupString, $controllerMatch)) {
            return $controllerMatch[1]; // Extracted controller class name
        }

         // Handle the new format: [Controller::class, 'method']
        if (preg_match_all("/'uses'\s*=>\s*\[([^\]]+),\s*'(.*?)'\]/", $groupString, $controllerMatches)) {
            return $controllerMatches[1][0];  // Return the controller class name from [Controller::class, 'method']
        }

        return null;
    }

    // Helper function for plural to singular conversion
    private function pluralToSingular($word)
    {
        // Basic rules for converting plural to singular
        $rules = [
            '/ies$/' => 'y', // e.g., "libraries" -> "library"
            '/oes$/' => 'o', // e.g., "heroes" -> "hero"
            '/s$/' => '',    // Remove trailing 's'
        ];
    
        foreach ($rules as $pattern => $replacement) {
            if (preg_match($pattern, $word)) {
                return preg_replace($pattern, $replacement, $word);
            }
        }
    
        return $word; // Return the word as-is if no rules match
    }
}


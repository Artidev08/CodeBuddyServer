<?php

/**
 *
 * @category Hq.ai
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <Hq.ai: 202306-V1.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\CodeRegister;
use App\Models\CodeRegisterFile;
use App\Models\CodeRegisterFileLog;
use App\Models\Project;
use Debugbar;
use App\Services\ControllerMethodExtractor;
use App\Services\ControllerMethodReplacer;
use Illuminate\Support\Facades\File;
use App\Traits\ManageGithubProcess;

class ControllersCodeOptimizeController extends Controller
{
    use ManageGithubProcess;
    protected $methodExtractor;
    protected $methodReplacer;
    protected $flow;
    protected $sync_type;

    public function __construct(ControllerMethodExtractor $methodExtractor, ControllerMethodReplacer $methodReplacer)
    {
        $this->methodExtractor = $methodExtractor;
        $this->methodReplacer = $methodReplacer;
        $this->flow = 'TRAVERS';
        $this->sync_type = 'controllers';
    }

    public function progress($code_register_id)
    {
        $sync_type = $this->sync_type;
        $code_register = CodeRegister::whereId($code_register_id)->first();
        $project = $code_register->project;
        return view('code-optimizer.controller.index', compact('code_register','sync_type','project'));
    }

    //Assign - It run complete then M2 allowed
    public function module1($code_register_id)
    {
        // try {
        $scopedProject = CodeRegister::whereId($code_register_id)->first();
        $sync_type = $this->sync_type;
      
        if (!$scopedProject) {
            return 'CR 404';
        }
        $project = $scopedProject->project;

        echo "CR #$scopedProject->id Found </br>";

        $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($scopedProject->id)->where('sync_type',$this->sync_type)
            ->whereStatus(CodeRegisterFile::STATUS_NOT_STARTED)
            ->first();

        if (!$scopedProjectFile) {
            return 'CRF 404';
        }

        echo "CRF #$scopedProjectFile->id Found </br>";
        
        if($project && $project->discovery_type == Project::DISCOVERY_TYPE_GITHUB){
            $scopedControllerCodeBase = $this->getFileContent($project,$scopedProjectFile->path,$scopedProjectFile->file_name);
            if(!isset($scopedControllerCodeBase['content'])) {
                $scopedProjectFile->update(['status' => CodeRegisterFile::STATUS_CANCELLED]);
                echo 'CRF M404 - Content Not Found </br>';
                return;
            }
            $scopedControllerForMethods = base64_decode($scopedControllerCodeBase['content']);
        }else{
            $scopedControllerForMethods = $this->step1($scopedProject, $scopedProjectFile);
        }
        $scopedMethods = $this->findAllMethods($scopedControllerForMethods);

        // Step 1.1. Get Scoped Methods Code in Array - VERIFIED
        if ($scopedProject->workflow == 'SPLCMT') {
            $scopedMethods = $this->findScopedMethods($scopedControllerForMethods);
            // return dd($scopedMethods);
        } elseif ($scopedProject->workflow == 'TRAVERS') {
            $scopedMethods = $this->findAllMethods($scopedControllerForMethods);
            // return dd($scopedMethods);
        } else {
            echo 'FLOW 404 </br>';
            return;
        }

        // Removing CodeRegisterFile if no method is found
        if(count($scopedMethods) == 0) {
            $scopedProjectFile->update(['status' => CodeRegisterFile::STATUS_CANCELLED]);
            echo 'CRF M404 - CANCELLED </br>';
            return;
        }

        // Thread Creation
        if ($scopedProject->thread_id == null) {
            $threadResponse = $this->createThread();
            $threadId = json_decode($threadResponse, true);
            echo 'TRDID #' . $threadId['id'] . ' Created </br>';
            $scopedProject->update(['thread_id' => $threadId['id']]);

            $scopedProjectFile->update(['thread_id' => $threadId['id']]);
            $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($scopedProject->id)
                ->where('sync_type',$this->sync_type)
                ->whereStatus(CodeRegisterFile::STATUS_NOT_STARTED)
                ->first();
        } else {
            $scopedProjectFile->update(['thread_id' => $scopedProject->thread_id]);
            $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($scopedProject->id)
                ->where('sync_type',$this->sync_type)
                ->whereStatus(CodeRegisterFile::STATUS_NOT_STARTED)
                ->first();
        }

        $ignoreMethods = ['__construct','__destruct'];
        foreach ($scopedMethods as $scopedMethodName => $scopedMethodCode) {

           // Checking and ignoring methods here
           if (in_array($scopedMethodName, $ignoreMethods)) {
                continue;
            }

            echo "Assigning #$scopedMethodName </br>";

            // Step 2. Get Scoped Snippet Code (Inc Helpers) - VERIFIED
            if ($scopedProject->workflow == 'SPLCMT') {
                $snippetContent = $this->step2($scopedMethodName, $scopedMethodCode);
            } elseif ($scopedProject->workflow == 'TRAVERS') {
                $snippetContent = $this->step2Travers($scopedMethodName, $scopedMethodCode);
            } else {
                echo 'FLOW 404 </br>';
                return;
            }
           
            // Create Code Register File Logs
            $developerContent = $this->step3($scopedProject, $scopedProjectFile, $scopedMethodName, $snippetContent);
        }

        $scopedProjectFile->update(['status' => CodeRegisterFile::STATUS_SCHEDULED]);
        echo "CRF #$scopedProjectFile->id Scheduled </br>";

        // Check CDF are SCHEDULED
        if (
            !CodeRegisterFile::whereCodeRegisterId($scopedProject->id)
                ->whereStatus(CodeRegisterFile::STATUS_NOT_STARTED)
                ->where('sync_type',$this->sync_type)
                ->first()
        ) {
            $scopedMethodTotalCount = CodeRegisterFile::whereCodeRegisterId($scopedProject->id)->where('sync_type',$this->sync_type)->count();
            $scopedProject->update(['status' => CodeRegister::STATUS_ASSIGNED, 'files' => $scopedMethodTotalCount]);
        }

        return 'M1 200 | ' . count($scopedMethods) . ' Tasks Assigned </br>';

        // } catch (\Throwable $th) {
        //     return $this->error("Something went wrong! " . $th->getMessage());
        // }
    }

    // Run - It run one by one
    public function module2($code_register_id)
    {
        $scopedProject = CodeRegister::whereId($code_register_id)->first();

        if (!$scopedProject) {
            return 'CR 404';
        }
        echo "CR #$scopedProject->id Found </br>";

        if ($scopedProject->status == CodeRegister::STATUS_COMPLETED) {
            return 'CR COMPLETED';
        }

        if ($scopedProject->agent_id != null) {
            $agent = Agent::whereId($scopedProject->agent_id)->first();

            if (!$agent) {
                return 'AGI 404';
            }

            $devAssistantId = $agent->gpt_code;
            echo "AGI #$agent->name Assigned </br>";
        } else {
            return 'AGI 404';
        }

        $agent = $scopedProject->agent;
        if (!$agent) {
            return 'CRA 404';
        }
        echo "CRA #$agent->id Found </br>";

        $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($code_register_id)
            ->where('sync_type',$this->sync_type)
            ->whereStatus(CodeRegisterFile::STATUS_RUNNING)
            ->first();

        if (!$scopedProjectFile) {
            $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($scopedProject->id)
                ->whereStatus(CodeRegisterFile::STATUS_SCHEDULED)
                ->where('sync_type',$this->sync_type)
                ->first();

            if (!$scopedProjectFile) {
                return 'CRF 404';
            }
        }

        if (!$scopedProjectFile) {
            return 'CRF 404';
        }
        echo "CRF #$scopedProjectFile->id Found </br>";

        // Checking Queue
        if (
            CodeRegisterFileLog::whereCodeRegisterId($scopedProject->id)
                ->whereStatus(CodeRegisterFileLog::STATUS_RUNNING)
                ->first()
        ) {
            return 'CRFL 403 - BUSY';
        }

        // Checking Available Files Logs
        $scopedProjectFileLog = CodeRegisterFileLog::whereCodeRegisterFileId($scopedProjectFile->id)
            ->whereStatus(CodeRegisterFileLog::STATUS_NOT_STARTED)
            ->first();
        if(!$scopedProjectFileLog){
            $scopedProjectFile->update(['status' => CodeRegisterFileLog::STATUS_READY]);
            $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($scopedProject->id)
                ->whereStatus(CodeRegisterFile::STATUS_SCHEDULED)
                ->where('sync_type',$this->sync_type)
                ->first();
            $scopedProjectFileLog = CodeRegisterFileLog::whereCodeRegisterFileId($scopedProjectFile->id)
                ->whereStatus(CodeRegisterFileLog::STATUS_NOT_STARTED)
                ->first();
        }
        if ($scopedProjectFileLog) {
            $scopedProjectFile->update(['status' => CodeRegisterFile::STATUS_RUNNING]);
            $scopedProject->update(['status' => CodeRegister::STATUS_RUNNING]);

            // Add Task in Thread
            $task = $scopedProjectFileLog->input_content;
            $output = $this->addAssistantTask(null,$scopedProjectFile->thread_id, $task);
            
            if (isset(json_decode($output, true)['error'])) {
                $log = json_decode($output, true)['error'];
                return 'CRFL 500 - ' . $log['message'] . ' | CODE: ' . $log['code'];
            }

            $payload = $scopedProjectFileLog->payload;
            $decodedPayload = $payload;
            $decodedPayload['msg_id'] = json_decode($output, true)['id'];

            echo "Assigned #$scopedProjectFileLog->method_name </br>";

            // Run Thread
            $threadId = $scopedProjectFile->thread_id;
            $runAssistantId = $this->runAssistantTask($devAssistantId, $threadId);

            echo "Running #$scopedProjectFileLog->method_name </br>";

            // Update Child Nodes
            $decodedPayload['run_id'] = json_decode($runAssistantId, true)['id'];
            $scopedProjectFileLog->payload = $decodedPayload;
            $scopedProjectFileLog->save();
            echo 'RunID #' . $decodedPayload['run_id'] . ' Found </br>';

            $scopedProjectFileLog->update(['status' => CodeRegisterFileLog::STATUS_RUNNING]);


            return 'M2 200';
        }

        return 'CRFL 404';
    }

    // Get Output
    public function module3($code_register_id)
    {
        $scopedProject = CodeRegister::whereId($code_register_id)->first();
        if (!$scopedProject) {
            return 'CR 404';
        }
        echo "CR #$scopedProject->id Found </br>";

        if ($scopedProject->status == CodeRegister::STATUS_COMPLETED) {
            return 'CR COMPLETED';
        }

        $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($code_register_id)
            ->where('sync_type',$this->sync_type)
            ->whereStatus(CodeRegisterFile::STATUS_RUNNING)
            ->first();

        if (!$scopedProjectFile) {
            return 'CRF 404';
        }
        echo "CRF #$scopedProjectFile->id Found </br>";

        $threadId = $scopedProjectFile->thread_id;

        // Checking Available Files Logs
        $scopedProjectFileLog = CodeRegisterFileLog::whereCodeRegisterFileId($scopedProjectFile->id)
            ->whereStatus(CodeRegisterFileLog::STATUS_RUNNING)
            ->first();

        if (!$scopedProjectFileLog) {
            return 'CRFL 404 in Loop #CRF' . $scopedProjectFile->id;
        }

        echo 'CRFL Found </br>';
        $payload = $scopedProjectFileLog->payload;
        $decodedPayload = $payload;
        $runId = $decodedPayload['run_id'];

        /// Check Message Status
        echo 'Run Status Checking </br>';
        $checkMessageStatus = $this->checkMessageStatus($threadId, $runId);
        $response = json_decode($checkMessageStatus, true);

        if ($response['status'] == 'completed') {
            echo '#' . $scopedProjectFileLog->id . ' - Status: Completed </br>';
        } else {
            echo '#' . $scopedProjectFileLog->id . ' - Status: InProgress </br>';
            return 'M3 402 - Already Queued | Ref #CRF' . $scopedProjectFile->id;
        }

        $output = $this->displayAssistantResponse($threadId);
        $responseMessages = json_decode($output, true);

        $message = $responseMessages['data'][0]['content'][0]['text']['value'];
        $message = str_replace(["```php", "```"], "", $message);
        echo 'Output Found </br>';

        $scopedProjectFileLog->update(['output_content' => $message, 'status' => CodeRegisterFileLog::STATUS_READY]);
        $project = $scopedProject->project;
        if($project && $project->handle_type == Project::HANDLE_TYPE_RAISE_TICKET){
            $this->pushTask($scopedProjectFileLog->id);
        }else{
           
        }

        return 'M3 200';
    }

    // Check & Update
    public function module4($code_register_id)
    {
        $scopedProject = CodeRegister::whereId($code_register_id)->first();

        if (!$scopedProject) {
            return 'CR 404';
        }
        echo "CR #$scopedProject->id Found </br>";

        $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($code_register_id)
            ->where('sync_type',$this->sync_type)
            ->whereStatus(CodeRegisterFile::STATUS_RUNNING)
            ->first();

        if (!$scopedProjectFile) {
            return 'CRF 404';
        }
        echo "CRF #$scopedProjectFile->id Found </br>";

        $scopedProjectFileLogs = CodeRegisterFileLog::whereCodeRegisterFileId($scopedProjectFile->id)
            ->whereStatus(CodeRegisterFileLog::STATUS_READY)
            ->get();

        if ($scopedProjectFileLogs->count() == 0) {
            return 'CRFL 404';
        }
        echo $scopedProjectFileLogs->count() . ' CRFL(s) Found </br>';

        $helperFilePath = 'app/Http/Helpers/helper.php';

        foreach ($scopedProjectFileLogs as $key => $scopedProjectFileLog) {
            // $scopedProjectFileLog->update(['status' => CodeRegisterFileLog::STATUS_CHECKED]);
            echo "CRFL #$scopedProjectFileLog->id Updated - CHECKED </br>";

            // Get Controller File Code...
            $scopedControllerCode = $this->step1($scopedProject, $scopedProjectFile);

            // Get Helper File Code...
            $helperNames = $this->getUsedHelperMethods($scopedControllerCode);
            $scopedHelperCode = $this->step1helper($scopedProject, $scopedProjectFile, $helperFilePath);

            // Get Request File Code...
            $requestNames = $this->getUsedRequestMethods($scopedControllerCode);
            $scopedRequestCode = $this->step2request($scopedProject, $requestNames);

            $message = $scopedProjectFileLog->output_content;

            // Optimized Content...

            if ($scopedProject->workflow == 'SPLCMT') {
                $optimizedContent = $this->step4($scopedControllerCode, $scopedHelperCode, $scopedRequestCode, $scopedProjectFileLog->method_name, $helperNames, $requestNames, $message);
            } elseif ($scopedProject->workflow == 'TRAVERS') {
                $optimizedContent = $this->step4Travers($scopedControllerCode, $scopedHelperCode, $scopedRequestCode, $scopedProjectFileLog->method_name, $helperNames, $requestNames, $message, $scopedControllerCode);
            } else {
                echo 'FLOW 404 </br>';
                return;
            }

            if (!isset($optimizedContent['method']) || $optimizedContent['method'] == null) {
                $scopedProjectFileLog->update(['status' => CodeRegisterFileLog::STATUS_CANCELLED]);
                echo "CRFL #$scopedProjectFileLog->id Updated - CANCELLED </br>";
                continue;
            }

            // Final Result...
            $this->step5($scopedProject, $scopedProjectFile, $helperFilePath, $optimizedContent, $requestNames);

            $scopedProjectFileLog->update(['status' => CodeRegisterFileLog::STATUS_COMPLETED]);
            echo "CRFL #$scopedProjectFileLog->id Updated - COMPLETED </br>";
        }

        // Check Next Task if not -> Completing
        if (
            !CodeRegisterFileLog::whereCodeRegisterFileId($scopedProjectFile->id)
                ->whereStatus(CodeRegisterFileLog::STATUS_NOT_STARTED)
                ->first()
        ) {
            $scopedProjectFile->update(['status' => CodeRegisterFile::STATUS_COMPLETED]);
        }

        echo ' Code Formatted - COMPLETED </br>';

        // Commands
        // $command = "format:controller '$scopedProject->base_path$scopedProjectFile->path$scopedProjectFile->file_name'";
        // return \Illuminate\Support\Facades\Artisan::call($command);

        $command = "phpcbf:run '$scopedProject->base_path$scopedProjectFile->path$scopedProjectFile->file_name'";
        \Illuminate\Support\Facades\Artisan::call($command);

        return 'M4 200';
    }

    // Format & Close
    public function module5($code_register_id)
    {

        $scopedProject = CodeRegister::whereId($code_register_id)->first();
        if (!$scopedProject) {
            return 'CR 404';
        }
        echo "CR #$scopedProject->id Found </br>";

        if ($scopedProject->status == CodeRegister::STATUS_COMPLETED) {
            return 'CR Already Completed :) </br>';
        }

        $scopedProjectFile = CodeRegisterFile::whereCodeRegisterId($code_register_id)
            ->where('sync_type',$this->sync_type)
            ->whereNotIn('status', [CodeRegisterFile::STATUS_COMPLETED])
            ->first();

        if (!$scopedProjectFile) {
            $scopedProject->update(['status' => CodeRegister::STATUS_COMPLETED]);

            $scopedProjectFiles = CodeRegisterFile::whereCodeRegisterId($code_register_id)->where('sync_type',$this->sync_type)->get();
            foreach ($scopedProjectFiles as $scopedProjectFileItem) {
                // Check Errors
                $output = [];
                $returnVar = 0;
                $command = './vendor/bin/phpcs --standard=PSR2 ' . $scopedProjectFileItem->path . '/' . $scopedProjectFileItem->file_name;
                exec($command, $output, $returnVar);
                return dd($returnVar);

                // Output the result
                if ($returnVar === 0) {
                    echo 'No coding standard violations found.</br>';
                } else {
                    echo "Coding standard violations found:\n";
                    foreach ($output as $line) {
                        echo $line . "\n";
                    }

                    // Fix Errors
                    $output = [];
                    $returnVar = 0;
                    $command = './vendor/bin/phpcbf --standard=PSR2 ' . $scopedProjectFileItem->path . '/' . $scopedProjectFileItem->file_name;
                    exec($command, $output, $returnVar);
                }
            }

            return 'CR Fully Executed 200';
        }
        return 'CR Found  </br>';
    }

    public function clearProgress($code_register_id)
    {
        $scopedProjectFiles = CodeRegisterFile::whereCodeRegisterId($code_register_id)->where('sync_type',$this->sync_type)->get();

        foreach ($scopedProjectFiles as $scopedProjectFile) {
            $scopedProjectFile->thread_id = null;
            $scopedProjectFile->status = 0;
            $scopedProjectFile->output_content = null;
            $scopedProjectFile->save();
            CodeRegisterFileLog::whereCodeRegisterFileId($scopedProjectFile->id)->delete();
        }

        return redirect(url('api/controllers-code-optimization/' . $code_register_id . '/progress') . '?action=stop');
    }

    public function syncMethods($code_register_id)
    {
        // Retrieve the project with the specified ID
        $scopedProject = CodeRegister::whereId($code_register_id)->first();
    
        if (!$scopedProject) {
            // Optionally handle the case where the project does not exist
            return redirect()->back()->withErrors('Project not found.');
        }
    
        // Get the base path from the project
        $base_path = $scopedProject->base_path;
    
        // Combine the base path with the expected controllers directory
        $folderPath = 'app/Http/Controllers';
        $controllersPath = $base_path .'/'. $folderPath;
    
        // Check if the directory exists
        if (!File::isDirectory($controllersPath)) {
            // Optionally handle the case where the directory does not exist
            return redirect()->back()->withErrors('Controllers directory not found.');
        }
    
        // Retrieve all PHP files in the controllers directory
        $files = File::allFiles($controllersPath);
        $controllers = [];
    
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $relativePath = $folderPath.'/'.$file->getRelativePath().'/';
                
                $controllers[] = [
                    'path_name' => $relativePath,
                    'controller' => $file->getFilename()
                ];
            }
        }
        
        if  (count($controllers) > 0) {

            foreach ($controllers as $controller) {
                // Check if an entry with the same file name and path already exists
                $existingFile = CodeRegisterFile::where('code_register_id', $code_register_id)
                                                ->where('sync_type',$this->sync_type)
                                                 ->where('file_name', $controller['controller'])
                                                 ->where('path', $controller['path_name'])
                                                 ->first();
            
                // If the file does not exist, create a new record
                if (!$existingFile) {
                    $codeRegisterFile = new CodeRegisterFile;
                    $codeRegisterFile->code_register_id = $code_register_id;
                    $codeRegisterFile->thread_id = null;
                    $codeRegisterFile->file_name = $controller['controller'];
                    $codeRegisterFile->path = $controller['path_name'];
                    $codeRegisterFile->status = 0;
                    $codeRegisterFile->output_content = null;
                    $codeRegisterFile->save();
                } 
            }
            
        }
    
        // Redirect to a specific route with query parameters
        return redirect(url('api/controllers-code-optimization/' . $code_register_id . '/progress') . '?action=stop');
    }

    // Stages
    private function step1($scopedProject, $scopedProjectFile)
    {
        return file_get_contents($scopedProject->base_path . '' . $scopedProjectFile->path . '' . $scopedProjectFile->file_name);
    }

    private function step2($scopedMethodName, $scopedMethodCode)
    {
        // Check if the 'method' parameter is blank
        if ($scopedMethodName == null || empty($scopedMethodCode)) {
            return response()->json(['message' => 'Method name is blank', 'status' => 400], 400);
        }

        $result = '';
        $result .= "\n/**start-hqai-m-$scopedMethodName**/\n";
        $result .= $scopedMethodCode;
        $result .= "\n/**end-hqai-m-$scopedMethodName**/\n";
        $result .= "\n\n";

        return $result;
    }

    private function step3($scopedProject, $scopedProjectFile, $scopedMethodName, $snippetContent)
    {
        $payload = CodeRegisterFileLog::PAYLOAD_STRUCTURE;

        $chk = CodeRegisterFileLog::where('code_register_id', $scopedProject->id)
            ->where('code_register_file_id', $scopedProjectFile->id)
            ->where('method_name', $scopedMethodName)
            ->where('status', 0) // Adjust the condition as needed
            ->first(); // Retrieve the first match

        if (!$chk) {
            $scopedProjectFileLog = CodeRegisterFileLog::create([
                'code_register_id' => $scopedProject->id,
                'code_register_file_id' => $scopedProjectFile->id,
                'method_name' => $scopedMethodName,
                'status' => 0, // Extracted
                'input_content' => $snippetContent,
                'payload' => $payload,
                'file_name' => $scopedProjectFile->file_name,
                'path' => $scopedProjectFile->path,
            ]);
        }

        return true;
    }

    private function step4($scopedControllerCode, $scopedHelperCode, $scopedRequestCode, $scopedMethodName, $helperNames, $requestNames, $message)
    {
        $scopedMethods = $this->findScopedMethods($message);
        if (!isset($scopedMethods[$scopedMethodName])) {
            $result = [];
            $result['method'] = null;
            $result['helper'] = null;
            $result['request'] = null;
        }

        if (count($scopedMethods) == 0) {
            return $result;
        }

        // Separate Method
        $optimizedMethodCode = $scopedMethods[$scopedMethodName];

        // Put Method Content in Controller File & Store
        $startScopeTag = "/**start-hqai-m-$scopedMethodName**/";
        $endScopeTag = "/**end-hqai-m-$scopedMethodName**/";

        $finalMethodCode = $this->appendScopedContent($startScopeTag, $endScopeTag, $scopedControllerCode, $optimizedMethodCode);
        // Separate Helpers

        $finalHelperCode = '';
        if (!$helperNames) {
            foreach ($helperNames as $helperName) {
                $scopedHelperSnippet = $this->getScopedHelperMethodCode($message, $helperName);

                // Put Helpers Content in Helper File & Store
                $startScopeTag = "/**start-hqai-h-$helperName**/";
                $endScopeTag = "/**end-hqai-h-$helperName**/";

                $finalHelperCode = $this->appendScopedContent($startScopeTag, $endScopeTag, $scopedHelperCode, $scopedHelperSnippet);
            }
        }

        // Separate Helpers
        $finalRequestCode = '';
        if (!$requestNames) {
            foreach ($requestNames as $requestName) {
                $scopedRequestSnippet = $this->getScopedRequestMethodCode($message, $requestName);

                // Put Helpers Content in Helper File & Store
                $startScopeTag = "/**start-hqai-r-$requestName**/";
                $endScopeTag = "/**end-hqai-r-$requestName**/";

                $finalRequestCode = $this->appendScopedContent($startScopeTag, $endScopeTag, $scopedRequestCode, $scopedRequestSnippet);
            }
        }

        $result = [];
        $result['method'] = $finalMethodCode;
        $result['helper'] = $finalHelperCode;
        $result['request'] = $finalRequestCode;

        return $result;
    }

    private function step4Travers($scopedControllerCode, $scopedHelperCode, $scopedRequestCode, $scopedMethodName, $helperNames, $requestNames, $message, $scopedControllerForMethods)
    {
        $scopedMethods = $this->findAllMethods($scopedControllerForMethods);

        if (!isset($scopedMethods[$scopedMethodName])) {
            $result = [];
            $result['method'] = null;
            $result['helper'] = null;
            $result['request'] = null;
        }

        if (count($scopedMethods) == 0) {
            return $result;
        }

        // Separate Method
      $optimizedMethodCode = $message;

        $finalMethodCode = '';
        $finalMethodCode = $this->methodReplacer->replaceMethodCode($scopedControllerForMethods, $scopedMethodName, $optimizedMethodCode);
        // return dd($finalMethodCode);
        // $finalMethodCode = $this->appendScopedTraversContent($scopedControllerForMethods, $scopedMethodName, $optimizedMethodCode);
        
        // Separate Helpers
        $finalHelperCode = '';

        // Separate Helpers
        $finalRequestCode = '';

        $result = [];
        $result['method'] = $finalMethodCode;
        $result['helper'] = $finalHelperCode;
        $result['request'] = $finalRequestCode;

        return $result;
    }

    private function step5($scopedProject, $scopedProjectFile, $helperFilePath, $optimizedContent, $requestNames)
    {
        // Write content to the project file
        file_put_contents($scopedProject->base_path . '' . $scopedProjectFile->path . '' . $scopedProjectFile->file_name, $optimizedContent['method']);

        // Write content to the helper file
        if ($optimizedContent['helper'] != '') {
            file_put_contents($scopedProject->base_path . '' . $helperFilePath, $optimizedContent['helper']);
        }

        // Write content to the request file
        if ($optimizedContent['request'] != '') {
            foreach ($requestNames as $requestName) {
                $requestFilePath = "app/Http/Requests/{$requestName}.php";
                file_put_contents($scopedProject->base_path . '' . $requestFilePath, $optimizedContent['request']);
            }
        }

        return true;
    }

    // End Stages

    // Children File Helper
    private function step1request($scopedProject, $requestLists)
    {
        foreach ($requestLists as $requestList) {
            $requestFilePath = "app/Http/Requests/{$requestList}.php";
            //  return $scopedProject->base_path . '' . $requestFilePath;
            $fileCode = file_get_contents($scopedProject->base_path . '' . $requestFilePath);
            return $scopeCode = $this->getScopedRequestMethodCode($fileCode, $requestList);
        }
    }

    private function step2request($scopedProject, $requestLists)
    {
        foreach ($requestLists as $requestList) {
            $requestFilePath = "app/Http/Requests/{$requestList}.php";
            //  return $scopedProject->base_path . '' . $requestFilePath;
            return file_get_contents($scopedProject->base_path . '' . $requestFilePath);
        }
    }

    private function step2Travers($scopedMethodName, $scopedMethodCode, $scopedHelperCode = null, $helperLists = null, $scopedRequestCode = null, $requestLists = null)
    {
        // Check if the 'method' parameter is blank
        if ($scopedMethodName == null || empty($scopedMethodCode)) {
            return response()->json(['message' => 'Method name is blank', 'status' => 400], 400);
        }

        $result = '';
        $result .= $scopedMethodCode;
        $result .= "\n\n";

        return $result;
    }

    private function step1helper($scopedProject, $scopedProjectFile, $helperFilePath)
    {
        return file_get_contents($scopedProject->base_path . '' . $helperFilePath);
    }
    // End Children File Helper

    // Helpers
    function findMessageById($messages, $msgId)
    {
        foreach ($messages['data'] as $message) {
            if (isset($message['id']) && $message['id'] === $msgId) {
                return $message;
            }
        }

        // If the loop completes without finding a match
        return null;
    }

    function findScopedMethods($scopedControllerCode)
    {
        $pattern = '/\/\*\*start-hqai-m-(.*?)\*\*\/(.*?)\/\*\*end-hqai-m-\1\*\*\//s';

        $matches = [];
        if (preg_match_all($pattern, $scopedControllerCode, $matches, PREG_SET_ORDER)) {
            $result = [];
            foreach ($matches as $match) {
                $customTag = $match[1];
                $content = trim($match[2]);
                $result[$customTag] = $content;
            }
            return $result;
        } else {
            return [];
        }
    }

    function findAllMethods($scopedControllerCode)
    {
        $methodNames = $this->methodExtractor->extractMethods($scopedControllerCode);
        return $methodNames;
    }

    function getUsedHelperMethods($scopedMethodCode)
    {
        $pattern = '/\/\*\*scoped-helpers:\s*(.*?)\s*\*\*\//';

        // Find all matches
        preg_match_all($pattern, $scopedMethodCode, $matches);

        // If matches are found, explode and store in an array
        if (!empty($matches[1])) {
            $resultArray = array_map('trim', explode(',', $matches[1][0]));
            return $resultArray;
        } else {
            // No matches found
            return [];
        }
    }

    function getScopedHelperMethodCode($fileCode, $methodName)
    {
        // Automatically detect starting and ending tags
        $pattern = '/\/\*\*start-hqai-h-' . preg_quote($methodName, '/') . '(.*?)\*\*\/(.*?)\/\*\*end-hqai-h-' . preg_quote($methodName, '/') . '\*\*\//s';

        $matches = [];
        if (preg_match_all($pattern, $fileCode, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $customTag = trim($match[1]);
                $content = trim($match[2]);
                // Check if the custom tag contains the specified method name
                if (strpos($customTag, $methodName) !== true) {
                    return $content;
                }
            }
        }

        return null;
    }

    function getUsedRequestMethods($scopedMethodCode)
    {
        $pattern = '/\/\*\*scoped-request:\s*(.*?)\s*\*\*\//';

        // Find all matches
        preg_match_all($pattern, $scopedMethodCode, $matches);

        // If matches are found, explode and store in an array
        if (!empty($matches[1])) {
            $resultArray = array_map('trim', explode(',', $matches[1][0]));
            return $resultArray;
        } else {
            // No matches found
            return [];
        }
    }

    function getScopedRequestMethodCode($fileCode, $methodName)
    {
        // Automatically detect starting and ending tags
        $pattern = '/\/\*\*start-hqai-r-' . preg_quote($methodName, '/') . '(.*?)\*\*\/(.*?)\/\*\*end-hqai-r-' . preg_quote($methodName, '/') . '\*\*\//s';

        $matches = [];
        if (preg_match_all($pattern, $fileCode, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $customTag = trim($match[1]);
                $content = trim($match[2]);
                // Check if the custom tag contains the specified method name
                if (strpos($customTag, $methodName) !== true) {
                    return $content;
                }
            }
        }

        return null;
    }

    function appendScopedContent($startScopeTag, $endScopeTag, $inputString, $newContent)
    {
        $pattern = '/' . preg_quote($startScopeTag, '/') . '(.*?)' . preg_quote($endScopeTag, '/') . '/s';

        // Replace the content between the markers
        $resultString = preg_replace($pattern, $startScopeTag . $newContent . $endScopeTag, $inputString);
        $resultString = str_replace($startScopeTag, $startScopeTag . "\n", $resultString);
        $resultString = str_replace($endScopeTag, "\n" . $endScopeTag, $resultString);

        return $resultString;
    }

    public function appendScopedTraversContent($originalCode, $methodName, $newMethodCode)
    {
        // Clean the new method code by removing code block markers
        $newMethodCode = preg_replace('/```php|```|`/', '', $newMethodCode);

        // Ensure the method name is correctly included in the new method code
        $replacementMethodPattern = '/function\s+' . preg_quote($methodName, '/') . '\s*\(/';
        if (!preg_match($replacementMethodPattern, $newMethodCode)) {
            return response()->json(['error' => 'Replacement code method name does not match'], 400);
        }

        $pattern = '/(?:\/\*\*(?:[^*]|\*(?!\/))*\*\/\s*)?'    // Optional docblock
                . '(?:\/\/[^\n]*\s*)*'                       // Optional line comments
                . '(public|protected|private)?\s*function\s+' . preg_quote($methodName, '/') . '\s*\([^)]*\)\s*\{[^\{]*$/m'; // Method signature and opening brace

        if (preg_match($pattern, $originalCode, $matches, PREG_OFFSET_CAPTURE)) {
            $methodStart = $matches[0][1];  // Position where the method starts
            $braceCount = 1;
            $methodEnd = $methodStart + strlen($matches[0][0]);

            // Step 2: Traverse the string to find the matching closing brace
            for ($i = $methodEnd; $i < strlen($originalCode); $i++) {
                if ($originalCode[$i] === '{') {
                    $braceCount++;
                } elseif ($originalCode[$i] === '}') {
                    $braceCount--;
                    if ($braceCount === 0) {
                        $methodEnd = $i + 1; // Include the closing brace
                        break;
                    }
                }
            }

            $methodCode = substr($originalCode, $methodStart, $methodEnd - $methodStart);

            $updatedCode = substr_replace($originalCode, $methodCode, $methodStart, $methodEnd - $methodStart);

            return $updatedCode;
         
         } else {
             return response()->json(['error' => 'Method not found'], 404);
         }
    }
    


//     public function appendScopedTraversContent($originalCode, $methodName, $newMethodCode)
// {
//     $newMethodCode = preg_replace('/```php|```|`/', '', $newMethodCode);
        
//     $methodDeclaration = "public function $methodName(";
//     $startPos = strpos($originalCode, $methodDeclaration);

//     if ($startPos !== false) {
//         $startBracePos = strpos($originalCode, "{", $startPos);
//         $braceCount = 1;
//         $endPos = $startBracePos;
//         while ($braceCount > 0) {
//             $endPos++;
//             if (substr($originalCode, $endPos, 1) == '{') {
//                 $braceCount++;
//             } elseif (substr($originalCode, $endPos, 1) == '}') {
//                 $braceCount--;
//             }
//         }
//         $originalCode = substr_replace($originalCode, $newMethodCode, $startPos, $endPos - $startPos + 1);
//     }

//     return $originalCode;
// }

    // Update Files::: Controller Name and Path store to ``CRF`` table.
    function uploadFileData()
    {
        $directory = 'D:\\wamp\\www\\my-project\\HQ.Ai\\app\\Http\\Controllers\\Admin';

        // Check if the directory exists
        if (is_dir($directory)) {
            // Get all PHP files in the directory
            $php_files = glob($directory . '/*.php');

            // Extract and display file names
            foreach ($php_files as $file_path) {
                $file_name = basename($file_path);
                CodeRegisterFile::create([
                    'code_register_id' => '2',
                    'file_name' => $file_name,
                    'path' => 'App\Http\Controllers\Admin', // Extracted
                    'status' => 0,
                ]);
            }
            return 'Upload File Count:' . ' ' . count($php_files);
        } else {
            echo "Directory not found: $directory";
        }
    }
    // End Helpers


    public function pushTask($id)
    {
        $log = CodeRegisterFileLog::where('id', $id)->first();
        $code_register = CodeRegister::where('id', $log->code_register_id)->first();
        $code_register_file = CodeRegisterFile::where('id', $log->code_register_file_id)->where('sync_type',$this->sync_type)->first();
        // if($log->status != CodeRegisterFileLog::STATUS_READY) {
        //     echo 'Task Not Ready for Push </br>';
        //     return 'Task Not Ready for Push ';
        // }
        // if($log->status == CodeRegisterFileLog::STATUS_COMPLETED) {
        //     echo 'Task Already Pushed </br>';
        //     return 'Task Already Pushed ';
        // }
        $headers = [
            'Accept' => 'application/json',
        ];
        $input = strip_tags(nl2br($log->input_content));
        $output = strip_tags(nl2br($log->output_content));
        $projectPath = ($code_register->project && $code_register->project->discovery_type == Project::DISCOVERY_TYPE_GITHUB) 
        ? "Project Repo Name: " . ($code_register->project->github_payload->repo_name ?? env('GITHUB_TEST_REPO_NAME')) 
        : "Project-path: $code_register->base_path";

        $task = "$projectPath<br>
        Path: {$code_register_file->path}/{$code_register_file->file_name}<br>
        Method: {$log->method_name} <br><br>
        File Code: {$input} <br><br>
        Assistant Suggestion: {$output}";
        
        $data = [
            'project_register_id' => $code_register->project->project_register_id,
            'error_msg' => $task,
            'request_link' => '#'
        ];
        
        $apiUrl = 'https://hq.defenzelite.com/api/v1/task/add-exception';
        $response = $this->postContentByCurl($apiUrl, $data, $headers);

        echo 'Task Pushed </br>';
        $log->status = CodeRegisterFileLog::STATUS_COMPLETED;
        $log->save();
        return 'Task Pushed ';

        if ($response && isset($response['status']) && $response['status'] === 'success') {
            return redirect()->back()->with('Success','Task Uploaded Successfully!.');

            return response()->json([
                'status' => 'success',
                'message' => 'Success',
                'title' => 'Task Uploaded Successfully!'
            ]);
        } else {
            return redirect()->back()->with('Error','Failed to add task!.');

            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'title' => 'Failed to add task!'
            ]);
        }
    }


}

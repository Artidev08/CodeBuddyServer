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
use Illuminate\Support\Facades\Route;
use App\Traits\ManageGithubProcess;

class ViewsCodeOptimizeController extends Controller
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
        Debugbar::disable();
        // SPLCMT: Uses hqai-m tags to extract and append
        // TRAVERS: Uses Php code syntaxes to extract and append
        $this->flow = 'TRAVERS';
        $this->sync_type = 'views';
    }

    public function progress($code_register_id)
    {
        $sync_type = $this->sync_type;
        $code_register = CodeRegister::whereId($code_register_id)->first();
        $project = $code_register->project;
        return view('code-optimizer.views.index', compact('code_register','sync_type','project'));
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
            $formHtmlCodeBase = $this->getFileContent($project,$scopedProjectFile->path,$scopedProjectFile->file_name);
            if(!isset($formHtmlCodeBase['content'])) {
                $scopedProjectFile->update(['status' => CodeRegisterFile::STATUS_CANCELLED]);
                echo 'CRF M404 - Content Not Found </br>';
                return;
            }
            $formHtml = base64_decode($formHtmlCodeBase['content']);
        }else{
            $formHtml = $this->step1($scopedProject, $scopedProjectFile);
        }


        // Removing CodeRegisterFile if no method is found
        if($formHtml == '') {
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

       
        // get comment of form
        $comment = $this->getFormComment($formHtml);

        $method = 'form';
        $formCombinedCode = "/**start-hqai-m-form-code**/\n\n" . $formHtml . "\n\n/**end-hqai-m-form-code**/";

        $routeDetail = $this->getControllerMethodCode($scopedProject->base_path,$comment,$project,true);
        if (isset($routeDetail['code'])) {
            $method = @$routeDetail['controller'] . ' | ' . @$routeDetail['method'];
            $methodCode = @$routeDetail['code'];
            $controllerCode = "/**start-hqai-m-controller-code**/\n\n" . $methodCode . "\n\n/**end-hqai-m-controller-code**/";
        
            // Check if use_statements exists, and concatenate before controller code
            if (!empty($routeDetail['use_statements'])) {
                $useStatements = "/**start-hqai-m-use-statement-code**/\n\n" . $routeDetail['use_statements'] . "\n\n/**end-hqai-m-use-statement-code**/";
                $formCombinedCode .= "\n\n" . $useStatements; // Add use statements before controller code
            }
        
            $formCombinedCode .= "\n\n" . $controllerCode; 
        }

        // Create Code Register File Logs
        $formContent = $this->step3($scopedProject, $scopedProjectFile, $method, $formCombinedCode);
        
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

        return 'M1 200 | Tasks Assigned </br>';

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

        return redirect(url('api/views-code-optimization/' . $code_register_id . '/progress') . '?action=stop');
    }

    // Stages
    private function step1($scopedProject, $scopedProjectFile)
    {
        return file_get_contents($scopedProject->base_path . '' . $scopedProjectFile->path . '' . $scopedProjectFile->file_name);
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

    function findScopedMethods($scopedControllerCode,$methodName = null)
    {
        if ($methodName !== null) {
            $pattern = preg_match_all('/public\s+function\s+(\w+)\s*\(/', $scopedControllerCode, $matches);
            $methods = $matches[1] ?? [];
            
            foreach ($methods as $index => $method) {
                if ($method === $methodName) {
                    // Find the start position of the current method
                    $methodStart = strpos($scopedControllerCode, "function {$method}");
            
                    // Find the start of the next method, or end of file if this is the last method
                    $nextMethodStart = isset($methods[$index + 1])
                        ? strpos($scopedControllerCode, "function {$methods[$index + 1]}")
                        : strlen($scopedControllerCode);
            
                    // Extract the code for the current method
                    $methodBody = substr($scopedControllerCode, $methodStart, $nextMethodStart - $methodStart);
            
                    // Adjust to stop before the next "public" keyword
                    $methodEnd = strrpos($methodBody, '}');
                    if ($methodEnd !== false) {
                        $methodBody = substr($methodBody, 0, $methodEnd + 1); // Include closing brace
                    }
            
                    // Remove comments and trim whitespace if needed
                    $cleanedBody = preg_replace('/(\/\/.*|\/\*[\s\S]*?\*\/)/', '', $methodBody);
                    $cleanedBody = trim($cleanedBody);
            
                    return $cleanedBody;
                }
            }
    
            return null; // Return null if the method is not found
        }else{
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
    }

    private function pushTask($id)
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

    // get code of method 
    private function getControllerMethodCode($base_path = null , $comment,$project , $is_use_statement = false)
    {
        // Match comment format {{-- start-hqai-m-path/to/controller@method-m-hqai-end --}}
        $regex = '/\{\{\s*--\s*start-hqai-m-([\w\/-]+)@([\w-]+)-m-hqai-end\s*--\s*\}\}/';
    
        if (preg_match($regex, $comment, $matches)) {
            $controllerPath = $matches[1]; // E.g., 'Admin/CodeRegisterController'
            $methodName = $matches[2];    // E.g., 'update'
    
            $controllerName = str_replace('/', DIRECTORY_SEPARATOR, $controllerPath) . ".php";
            if($project && $project->discovery_type == Project::DISCOVERY_TYPE_GITHUB){
                $fileContentsBase = $this->getFileContent($project,'app/Http/Controllers/',$controllerPath. ".php");
                if(isset($fileContentsBase['status']) && $fileContentsBase['status'] == 'error') {
                    echo 'CRF M404 - Content Not Found </br>';
                    return;
                }
                $fileContents = base64_decode($fileContentsBase['content']);
            }else{
                $controllerFile = $base_path."app/Http/Controllers/" .$controllerName;
                if (!file_exists($controllerFile)) {
                    return "Controller file '$controllerFile' does not exist.";
                }
                $fileContents = file_get_contents($controllerFile);
            }

            $scopedMethodsCode = $this->findScopedMethods($fileContents,$methodName);
            if (isset($scopedMethodsCode)) {
                $response = [
                    'controller' => $controllerPath,
                    'method' => $methodName,
                    'code' => $scopedMethodsCode,
                ];
    
                // If $is_use_statement is true, extract all code above the class declaration
                if ($is_use_statement) {
                    $useStatementCode = $this->extractNamespaceAndUses($fileContents);
                    $response['use_statements'] = $useStatementCode;
                }

                return $response;
            }
    
            return "Method '$methodName' not found in controller '$controllerPath'.";
        }
    
        return "Invalid comment format: '$comment'.";
    }
    
    /**
     * Extracts the namespace and use statements (everything above the class declaration).
     */
    private function extractNamespaceAndUses($fileContents)
    {
        // Match everything from the start of the file until the class declaration
        if (preg_match('/^(.*?)(?=class\s+\w+)/s', $fileContents, $matches)) {
            $namespaceCode = trim($matches[1]);

            // Remove `<?php` if it exists at the beginning
            $namespaceCode = preg_replace('/^<\?php\s*/', '', $namespaceCode);
            $namespaceCode = preg_replace('/^namespace\s+.*?;/m', '', $namespaceCode); // Remove namespace line

            return trim($namespaceCode);
        }
        return "Namespace and imports not found";
    }
    // get comment from form 
    private function getFormComment($formHtml)
    {
        $html = $formHtml;
        // Regex to match the specific comment pattern
        $pattern = '/\{\{\s*--\s*start-hqai-m-[\w\/@-]+-m-hqai-end\s*--\s*\}\}/';
    
        // Search for the comment in the provided HTML
        if (preg_match($pattern, $formHtml, $matches)) {
            return $matches[0]; // Return the full matched comment
        }
    
        // if not added return default comment from form action
        return $this->getFormAction($formHtml);
    }

    // return comment from form action
    private function getFormAction($formHtml)
    {
        // Match all 'action' attributes using the route() helper
        preg_match_all('/action="{{\s*route\((.*?)\)\s*}}"/', $formHtml, $matches);
    
        if (!empty($matches[1])) {
            foreach ($matches[1] as $routeData) {
                $routeParts = explode('.', trim($routeData, "'"));
                if (count($routeParts) >= 3) {
                    $subNamespace = ucfirst($routeParts[1]); // e.g., "Admin"
                    
                    // Handle plural to singular, remove _ and -, and convert to PascalCase
                    $rawController = str_replace(['_', '-'], ' ', $routeParts[2]); // Replace _ and - with space
                    $pascalController = str_replace(' ', '', ucwords($rawController)); // Convert to PascalCase
                    $controller = $this->pluralToSingular($pascalController) . 'Controller'; // Singularize and append "Controller"
    
                    // Extract only the last part of the route as the method
                    $method = isset($routeParts[3])
                    ? explode('$', preg_replace('/\w+\(.*?\)/', '', $routeParts[3]))[0] // Remove function calls like secureToken()
                    : 'index'; // Default to 'index' if not provided
                    $method = preg_replace("/[^a-zA-Z0-9_]/", '', $method ?? 'index'); // Remove unwanted characters

                    // Format the comment
                    $comment = "{{-- start-hqai-m-$subNamespace/$controller@$method-m-hqai-end --}}";
                    return $comment;
                }
            }
        }
    
        return 'No route found';
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

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
use App\Models\Category;
use App\Models\CodeRegister;
use App\Models\CodeRegisterAgent;
use App\Models\CodeRegisterFile;
use App\Models\CodeRegisterAgentLog;
use App\Models\Project;
use Illuminate\Http\Request;

class CodeOptimizeController extends Controller
{
    protected $sync_type;

    public function __construct()
    {
        $this->sync_type = 'controllers';
    }


    public function pushTask($id)
    {
        $log = CodeRegisterAgentLog::where('id', $id)->first();
        $code_register = CodeRegister::where('id', $log->code_register_id)->first();
        $code_register_file = CodeRegisterFile::where('id', $log->code_register_file_id)->where('sync_type',$this->sync_type)
        ->first();
        // if($log->status != CodeRegisterAgentLog::STATUS_READY) {
        //     echo 'Task Not Ready for Push </br>';
        //     return 'Task Not Ready for Push ';
        // }
        // if($log->status == CodeRegisterAgentLog::STATUS_COMPLETED) {
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
        $log->status = CodeRegisterAgentLog::STATUS_COMPLETED;
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

    public function runAgent(Request $request)
    { 
        $code_register_id = $request->code_register_id;
        $scopedRegister = CodeRegister::whereId($code_register_id)->first();
        $agent_id = $request->agent_id;
        if($agent_id == 0){
            $scopedAgent = new CodeRegisterAgent();
            $scopedAgent->code_register_id = $scopedRegister->id;
            $scopedAgent->project_id = $scopedRegister->project->id;
            $scopedAgent->name = $request->name;
            $scopedAgent->gpt_code = $agent->gpt_code ?? null;
            $scopedAgent->model_id = $request->model_id;
            $scopedAgent->scope_ids = $request->scope_ids;
            $scopedAgent->prompt = $request->prompt;
            $scopedAgent->save();

            if (!$request->gpt_code) {
                $model = Category::find($request->model_id);
                $addResponse = $this->addAssistant($scopedAgent->name, generateAgentPrompt($scopedAgent), $model->name);
                $addresponseArray = json_decode($addResponse, true);
                if (isset($addresponseArray['error'])) {
                    $scopedAgent->delete();
                    return response()->json(
                        [
                            'status'=>'error',
                            'message' => 'Error',
                            'title' => $addresponseArray['error']['message']
                        ]
                    );
                }
                $scopedAgent->gpt_code = $addresponseArray['id'];
                $scopedAgent->save();
            }

        }else{
            $scopedAgent = CodeRegisterAgent::where('id', $request->agent_id)->first();
        }
        // try {
        if (!$scopedAgent) {
            return 'SA 404';
        }
        if (!$scopedRegister) {
            return 'CR 404';
        }
        $project = $scopedRegister->project;

        echo "CR #$scopedRegister->id Found </br>";

        $scopedRegisterFiles = CodeRegisterFile::whereCodeRegisterId($scopedRegister->id)->get();


        // Thread Creation
        if ($scopedAgent->thread_id == null) {
            $threadResponse = $this->createThread();
            $threadId = json_decode($threadResponse, true);
            echo 'TRDID #' . $threadId['id'] . ' Created </br>';
            $scopedAgent->update(['thread_id' => $threadId['id']]);
        }

        
        foreach ($scopedRegisterFiles as $file){
            $this->step1($file,$scopedAgent,$project,$request);
            $file->update(['thread_id' => $scopedAgent->thread_id]);
        }
        $scopedAgent->update(['status' => CodeRegisterAgent::STATUS_SCHEDULED]);

        $codeRegisterAgentLog = CodeRegisterAgentLog::where('code_register_id', $scopedRegister->id)
            ->where('code_register_agent_id', $scopedAgent->id)
            ->where('project_id', $project->id)->first();

        if($codeRegisterAgentLog){
            $this->module2($scopedRegister->id,$scopedAgent->id);
        }

        $this->module3($scopedRegister->id,$scopedAgent->id);

        echo 'Step1 200 | Files Assigned </br>';
        return redirect()->back()->with('Success','Step1 Completed Successfully!.');

        // } catch (\Throwable $th) {
        //     return $this->error("Something went wrong! " . $th->getMessage());
        // }
    }

    private function step1($scopedProjectFile,$agent,$project,$request)
    {
        $payload = CodeRegisterAgentLog::PAYLOAD_STRUCTURE;

        $chk = CodeRegisterAgentLog::where('code_register_id', $scopedProjectFile->code_register_id)
            ->where('code_register_file_id', $scopedProjectFile->id)
            ->where('code_register_agent_id', $agent->id)
            ->where('project_id', $project->id)
            ->first(); 

        if (!$chk) {
            $scopedProjectFileLog = CodeRegisterAgentLog::create([
                'code_register_id' => $scopedProjectFile->code_register_id,
                'project_id' => $project->id,
                'code_register_file_id' => $scopedProjectFile->id,
                'code_register_agent_id' => $agent->id,
                'prompt' => $request->prompt,
                'status' => 0, // Extracted
                'input_content' => $scopedProjectFile->output_content,
                'payload' => $payload,
                'path' => $scopedProjectFile->path,
            ]);
        }

        return true;
    }

    public function module2($code_register_id,$agent_id)
    {
        $scopedProject = CodeRegister::whereId($code_register_id)->first();
        $agent = CodeRegisterAgent::where('id', $agent_id)->first();

        if (!$scopedProject) {
            return 'CR 404';
        }

        echo "CR #$scopedProject->id Found </br>";

        if ($scopedProject->status == CodeRegister::STATUS_COMPLETED) {
            return 'CR COMPLETED';
        }

        if ($agent != null) {

            $devAssistantId = $agent->gpt_code;
            echo "AGI #$agent->name Assigned </br>";
        } else {
            return 'AGI 404';
        }

        if (!$agent) {
            return 'CRA 404';
        }
        echo "CRA #$agent->id Found </br>";

        $codeRegisterAgentLog = CodeRegisterAgentLog::where('code_register_id', $scopedProject->id)
        ->where('code_register_agent_id', $agent->id)
        ->where('status', CodeRegisterAgentLog::STATUS_RUNNING)->first();
        
        // Checking Queue
        if ($codeRegisterAgentLog) {
            return 'CRFL 403 - BUSY';
        }

        // Checking Available Files Logs
        $scopedProjectFileLog = CodeRegisterAgentLog::where('code_register_id', $scopedProject->id)
            ->where('code_register_agent_id', $agent->id)
            ->whereStatus(CodeRegisterAgentLog::STATUS_NOT_STARTED)
            ->first();

        if ($scopedProjectFileLog) {
            $scopedProject->update(['status' => CodeRegister::STATUS_RUNNING]);
            $agent->update(['status' => CodeRegisterAgent::STATUS_RUNNING]);

            // Add Task in Thread
            $task = $scopedProjectFileLog->input_content;
            $output = $this->addAssistantTask(null,$agent->thread_id, $task);
            if (isset(json_decode($output, true)['error'])) {
                $log = json_decode($output, true)['error'];
                return 'CRFL 500 - ' . $log['message'] . ' | CODE: ' . $log['code'];
            }

            $payload = $scopedProjectFileLog->payload;
            $decodedPayload = $payload;
            $decodedPayload['msg_id'] = json_decode($output, true)['id'];

            echo "Assigned #$scopedProjectFileLog->method_name </br>";

            // Run Thread
            $threadId = $agent->thread_id;
            $runAssistantId = $this->runAssistantTask($devAssistantId, $threadId);

            echo "Running #$scopedProjectFileLog->method_name </br>";

            // Update Child Nodes
            $decodedPayload['run_id'] = json_decode($runAssistantId, true)['id'];
            $scopedProjectFileLog->payload = $decodedPayload;
            $scopedProjectFileLog->save();
            echo 'RunID #' . $decodedPayload['run_id'] . ' Found </br>';

            $scopedProjectFileLog->update(['status' => CodeRegisterAgentLog::STATUS_RUNNING]);
            return redirect()->back()->with('Success','Step2 Completed Successfully!.');

        }
        return redirect()->back()->with('Success','Step2 Completed Successfully!.');
    }

    // Get Output
    public function module3($code_register_id,$agent_id)
    {
        $scopedProject = CodeRegister::whereId($code_register_id)->first();
        $agent = CodeRegisterAgent::where('id', $agent_id)->first();

        $scopedProject = CodeRegister::whereId($code_register_id)->first();
        if (!$scopedProject) {
            return 'CR 404';
        }
        echo "CR #$scopedProject->id Found </br>";

        if ($scopedProject->status == CodeRegister::STATUS_COMPLETED) {
            return 'CR COMPLETED';
        }

        $threadId = $agent->thread_id;

        // Checking Available Files Logs
        $scopedProjectFileLogs = CodeRegisterAgentLog::where('code_register_agent_id',$agent->id)
            ->whereStatus(CodeRegisterAgentLog::STATUS_RUNNING)
            ->get();


        echo 'CRFL Found </br>';
        foreach ($scopedProjectFileLogs as $scopedProjectFileLog){
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
                return 'M3 402 - Already Queued | Ref #CRF' . $scopedProjectFileLog->id;
            }
    
            $output = $this->displayAssistantResponse($threadId);
            $responseMessages = json_decode($output, true);
    
            $message = $responseMessages['data'][0]['content'][0]['text']['value'];
            $message = str_replace(["```php", "```"], "", $message);
            echo 'Output Found </br>';
    
            $scopedProjectFileLog->update(['output_content' => $message, 'status' => CodeRegisterAgentLog::STATUS_READY]);
            $agent->update(['status' => CodeRegisterAgent::STATUS_SCHEDULED]);
        }
        $nextFileLogs = CodeRegisterAgentLog::where('code_register_agent_id',$agent->id)
            ->whereStatus(CodeRegisterAgentLog::STATUS_NOT_STARTED)
            ->first();
        if(!$nextFileLogs){
            $agent->update(['status' => CodeRegisterAgent::STATUS_COMPLETED]);
        }
        return redirect()->back()->with('Success','Step3 Completed Successfully!.');

    }

    public function clearProgress($code_register_id)
    {
        if(request()->agent_id){
            $scopedProjectAgents = CodeRegisterAgent::where('id',request()->agent_id)->whereCodeRegisterId($code_register_id)->get();
        }else{
            $scopedProjectAgents = CodeRegisterAgent::whereCodeRegisterId($code_register_id)->get();
        }

        foreach ($scopedProjectAgents as $scopedProjectFile) {
            $scopedProjectFile->thread_id = null;
            $scopedProjectFile->status = CodeRegisterAgent::STATUS_NOT_STARTED;
            $scopedProjectFile->save();
            $scopedProjectFile->delete();
            CodeRegisterAgentLog::where('code_register_agent_id', $scopedProjectFile->id)
            ->update(['status' => CodeRegisterAgentLog::STATUS_NOT_STARTED]);

            $scopedProjectFile->codeRegister->update(['status' => CodeRegister::STATUS_NOT_STARTED]);
        }

        return redirect()->back()->with('Success','Logs Cleared Successfully!.');
    }
}

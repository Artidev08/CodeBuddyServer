<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessStep1Job;
use App\Jobs\ProcessStep5Job;
use App\Jobs\ProcessStep3Job;
use App\Jobs\ProcessStep4Job;
use App\Models\CodeRegister;
use App\Models\CodeRegisterAgent;
use App\Models\CodeRegisterAgentLog;
use App\Services\OpenAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentProcessingController extends Controller
{
    protected $openAiService;

    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }
    public function assignFilesToAgent(Request $request)
    {
        // try {
            $code_register_id = $request->code_register_id;
            $agent_id = $request->agent_id;
            $scopedAgent = CodeRegisterAgent::findOrFail($agent_id);
            $scopedRegister = CodeRegister::findOrFail($code_register_id);
            ProcessStep1Job::dispatch($scopedRegister->id, $scopedAgent->id);
            
            // switch (true) {
            //     case $this->isTaskAndLogsNotStartedOrScheduled($scopedAgent):
            //         ProcessStep1Job::dispatch($scopedRegister->id, $scopedAgent->id);

            //     // case $this->isTaskReady($scopedAgent):
            //     //     ProcessStep3Job::dispatch($scopedRegister->id, $scopedAgent->id);

            //     // case $this->isTaskChecked($scopedAgent):
            //     //     $allPassedLogs = $scopedAgent->codeRegisterAgentLogs->where('status', CodeRegisterAgentLog::STATUS_PASSED);
            //     //     if($allPassedLogs->count() > 0) {
            //     //         ProcessStep4Job::dispatch($scopedAgent->id, $allPassedLogs->first()->id);
            //     //     }

            //     // case $this->isTaskCompleted($scopedAgent):
            //     //     ProcessStep5Job::dispatch($scopedRegister->id, $scopedAgent->id);

            //     // case $this->isTaskClosed($scopedAgent):
            //     //     $scopedAgent->codeRegister->update(['status' => CodeRegisterAgent::STATUS_CLOSED]);

            //     default:
            //         // Return an appropriate message if no matching case is found
            // }

            $scopedAgent = CodeRegisterAgent::find($agent_id);
            return response()->json([
                'status' => 'Success',
                'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],
                'message' => 'The process is running in the background, please wait...'
            ]);
            // // Process based on the agent's status
            // switch (true) {
            //     case $this->isTaskAndLogsNotStartedOrScheduled($scopedAgent):
            //         return $this->processNotStartedOrScheduled($scopedAgent, $scopedRegister);

            //     case $this->isTaskReady($scopedAgent):
            //         return $this->processReady($scopedAgent);

            //     case $this->isTaskChecked($scopedAgent):
            //         return $this->processChecked($scopedAgent);

            //     case $this->isTaskCompleted($scopedAgent):
            //         return $this->processCompleted($scopedAgent);

            //     case $this->isTaskLogsPassedToAgent($scopedAgent):
            //         return $this->processPassedToAgent($scopedAgent);
                    
            //     case $this->isTaskLogsPassedToAgent($scopedAgent):
            //         return $this->processPassedToAgent($scopedAgent);

            //     case $this->isTaskClosed($scopedAgent):
            //         $scopedAgent->codeRegister->update(['status' => CodeRegisterAgent::STATUS_CLOSED]);
            //         return response()->json(['status' => 'Success','task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'], 'message' => 'Task is already closed']);

            //     default:
            //         // Return an appropriate message if no matching case is found
            //         return response()->json([
            //             'status' => 'error',
            //             'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],
            //             'message' => 'Unknown agent status or invalid status transition.'
            //         ]);
            // }
           
            
        // } catch (\Exception $e) {
        //     return response()->json(['status' => 'error', 'message' => "Error in assignFilesToAgent: " . $e->getMessage()]);
        // }
    }

    private function isTaskAndLogsNotStartedOrScheduled($scopedAgent)
    {
        return in_array($scopedAgent->status, [CodeRegisterAgent::STATUS_NOT_STARTED, CodeRegisterAgent::STATUS_SCHEDULED]);
    }

    private function isTaskReady($scopedAgent)
    {
        return in_array($scopedAgent->status, [CodeRegisterAgent::STATUS_READY]);
    }

    private function isTaskClosed($scopedAgent)
    {
        return in_array($scopedAgent->status, [CodeRegisterAgent::STATUS_CLOSED]);
    }

    private function isTaskChecked($scopedAgent)
    {
        return in_array($scopedAgent->status, [CodeRegisterAgent::STATUS_CHECKED, CodeRegisterAgent::STATUS_PASSED]);
    }

    private function isTaskCompleted($scopedAgent)
    {
        return in_array($scopedAgent->status, [CodeRegisterAgent::STATUS_COMPLETED]);
    }

    private function isTaskLogsPassedToAgent($scopedAgent)
    {
        return in_array($scopedAgent->status, [CodeRegisterAgent::STATUS_PASSED, CodeRegisterAgent::STATUS_PROCESSED]);
    }

    private function processNotStartedOrScheduled($scopedAgent, $scopedRegister)
    {
        // Dispatch the job for file assignment
        ProcessStep1Job::dispatch($scopedRegister->id, $scopedAgent->id);

        return response()->json([
            'status' => 'Success',
            'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],
            'message' => 'The process is running in the background, please wait...'
        ]);
    }

    private function getAgentLogs($scopedAgent, $scopedRegister)
    {
        return CodeRegisterAgentLog::where('code_register_id', $scopedRegister->id)
            ->where('code_register_agent_id', $scopedAgent->id)
            ->whereIn('status', [CodeRegisterAgentLog::STATUS_NOT_STARTED, CodeRegisterAgentLog::STATUS_SCHEDULED])
            ->get();
    }

    private function processReady($scopedAgent)
    {
        $getStep2Task = getStep2Task($scopedAgent);

        $data = ['role' => 'user', 'content' => $getStep2Task];

        $payload = $scopedAgent->payload;
        $decodedPayload = $payload;

        $responseData = $this->openAiService->addAssistantTask(null, $scopedAgent->thread_id, $getStep2Task, $data);    

        if ($responseData && isset($responseData['error'])) {
            return 'CRFL 500 - ' . $responseData . ' | CODE: ' . @$responseData['error']['code'];
        }

        $decodedPayload['msg_id'] = $responseData['id'];

        // Run Thread
        $runAssistantResponse = $this->openAiService->runAssistantTask($scopedAgent->gpt_code, $scopedAgent->thread_id);

        // Update Child Nodes
        $decodedPayload['run_id'] = $runAssistantResponse['id'];
        $scopedAgent->payload = $decodedPayload;
        $scopedAgent->save();
        $scopedAgent->update(['input_content' => $getStep2Task, 'status' => CodeRegisterAgent::STATUS_CHECKED]);

        return response()->json(['status' => 'Success', 'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],'message' => 'Ready task is complete']);
    }
    

    private function processChecked($scopedAgent)
    {
        $payload = $scopedAgent->payload;
        $decodedPayload = $payload;
        $runId = $decodedPayload['run_id'];
        $threadId = $scopedAgent->thread_id;

        $messageStatusResponse = $this->openAiService->checkMessageStatus($threadId, $runId);

        if ($messageStatusResponse['status'] == 'completed') {
            $displayAssistantResponse = $this->openAiService->displayAssistantResponse($threadId);

            $message = $displayAssistantResponse['data'][0]['content'][0]['text']['value'];
            if($message){
                $scopedAgent->update(['output_content' => $message, 'status' => CodeRegisterAgent::STATUS_COMPLETED]);
            }
        }

        return response()->json(['status' => 'Success', 'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],'message' => 'Checked task is complete']);
    }

    private function processCompleted($scopedAgent)
    {
        $payload = $scopedAgent->output_content;

        if(!$scopedAgent->output_content){
            // $this->processReady($scopedAgent);
        }else{
            // Use the helper function to extract and decode the JSON
            $decodedArray = $this->extractAndDecodeJson($payload);
            $this->updateAgentLogs($scopedAgent, $decodedArray);
        }

        return response()->json(['status' => 'Success', 'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],'message' => 'Completed task is complete']);
    }

    /**
     * Extract and decode JSON from the given payload.
     *
     * @param string $payload The payload to extract JSON from.
     * @return array|string The decoded JSON as an associative array, or an error message if there's an issue.
     */
    private function extractAndDecodeJson($payload)
    {
        // Extract JSON content between { and }
        preg_match('/\{.*\}/s', $payload, $matches);

        // Check if no valid JSON was found
        if (empty($matches[0])) {
            return response()->json(['status' => 'error', 'message' => "No valid JSON found in payload"]);
        }

        // Decode JSON into an associative array
        $decodedArray = json_decode($matches[0], true);

        // Check for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['status' => 'error', 'message' => 'JSON Decode Error: ' . json_last_error_msg()]);
        }

        return $decodedArray;
    }


    private function updateAgentLogs($scopedAgent, $decodedArray)
    {
        $codeRegisterAgentLogs = $scopedAgent->codeRegisterAgentLogs;

        foreach ($codeRegisterAgentLogs as $log) {
            $fileName = basename($log->path);
            if (array_key_exists($fileName, $decodedArray) && $decodedArray[$fileName] !== false) {
                $log->update(['status' => CodeRegisterAgentLog::STATUS_PASSED]);
                Log::info("Log updated to PASSED: {$log->path}");
            } else {
                $log->update(['status' => CodeRegisterAgentLog::STATUS_NO_CHANGE_NEEDED]);
                Log::info("Log updated to NO_CHANGE_NEEDED: {$log->path}");
            }
        }
        $scopedAgent->update(['status' => CodeRegisterAgent::STATUS_PASSED]);

    }

    private function processPassedToAgent($scopedAgent)
    {
        // Get all logs with the 'passed' status
        $allPassedLogs = $scopedAgent->codeRegisterAgentLogs->where('status', CodeRegisterAgentLog::STATUS_PASSED);
    
        // Get all logs with the 'checked' status
        $allCheckedLogs = $scopedAgent->codeRegisterAgentLogs->where('status', CodeRegisterAgentLog::STATUS_CHECKED);
    
        // If no passed logs and no checked logs, mark the agent as completed
        if ($allPassedLogs->isEmpty() && $allCheckedLogs->isEmpty()) {
            $scopedAgent->update(['status' => CodeRegisterAgent::STATUS_CLOSED]);
            $scopedAgent->codeRegister->update(['status' => CodeRegister::STATUS_COMPLETED]);
            return response()->json(['status' => 'Success', 'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],'message' => 'Agent marked as completed. No passed or checked logs.']);
        }
    
        // Process passed logs
        $payload = $scopedAgent->output_content;
        $decodedArray = $this->extractAndDecodeJson($payload);
    
        foreach ($allPassedLogs as $passedLog) {
            // Generate Step 3 Task based on decoded data
            $getStep3Task = getStep3Task($decodedArray, $passedLog);
            // Update the passed log with the task content
            $passedLog->update(['issue_content' => $getStep3Task]);
    
            // Dispatch job to process the passed log
            ProcessStep4Job::dispatch($scopedAgent->id, $passedLog->id);
        }
    
        // Process checked logs
        foreach ($allCheckedLogs as $checkedLog) {
            // Dispatch job to process the checked log
            ProcessStep5Job::dispatch($scopedAgent->id, $checkedLog->id);
        }
    
        // Return success response
        return response()->json(['status' => 'Success', 'task_color' => CodeRegisterAgent::STATUSES[$scopedAgent->status]['color'],'message' => 'Passed task is scheduled']);
    }
    
}

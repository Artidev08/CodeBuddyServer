<?php
/**
 *
 * @category Hq.ai
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <Hq.ai: 202309-V1.2>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Traits\CanSendMail;
use App\Traits\HasResponse;
use App\Traits\CanManageFiles;
use App\Traits\ControlOrder;
use App\Traits\ImageIntervation;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Agent;
use App\Models\CodeRegister;
use App\Models\CodeRegisterAgent;
use App\Models\ScenarioAgent;
use App\Models\ScenarioRunner;
use App\Models\ScenarioRunnerLog;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ControlOrder;
    use HasResponse,CanManageFiles,CanSendMail,ImageIntervation;
    public $api_key;
    public function __construct()
    {
        $this->api_key = env('CHATGPT_API_KEY');
    }
    
    public function convertPromptDataIntoPrompt($agent, $givenContent = null)
    {
        $prompt = "";
        $country = $agent->myCountry ? $agent->myCountry->name : '';
        $dapartment = $agent->department ? $agent->department->name : '';
        $designation = $agent->designation ? $agent->designation->name : '';
        $prompt .= "Your name is $agent->name ($agent->gender) lives in $country \n";
        $prompt .= "You are working in $dapartment department as a $designation \n";
        // condition
        if (isset($agent->prompt['task'])) {
            $task = $agent->prompt['task'];
            $prompt .= "Task: \n $task \n";
        }
        if (isset($agent->prompt['guideline'])) {
            $guideline = $agent->prompt['guideline'];
            $prompt .= "Guideline: \n $guideline \n";
        }
        if (isset($agent->prompt['criteria'])) {
            $criteria = $agent->prompt['criteria'];
            $prompt .= "Criteria: \n $criteria \n";
        }
        if (isset($agent->prompt['about_us'])) {
            $aboutUs = $agent->prompt['about_us'];
            $prompt .= "About Us: \n $aboutUs \n";
        }
        if (isset($agent->prompt['note'])) {
            $note = $agent->prompt['note'];
            $prompt .= "Note: \n $note) \n";
        }
        if (isset($agent->prompt['output_format'])) {
            $outputFormat = $agent->prompt['output_format'];
            $prompt .= "Output Format: \n $outputFormat \n";
        }
        $prompt .= "Given Content: \n $givenContent \n";

        return $prompt;
    }

    
    //get result from api of GPT
    public function responseOutputContent($prompt)
    {
        $response = Http::withToken($this->api_key)
        ->timeout(600)
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [[
                'role' => 'system',
                'content' => $prompt
            ]],
        ])
        ->throw()
        ->json();
        $output = $response['choices'][0]['message']['content'];
        return $output;
    }

    //Get all assistants
    
    function getAllAssistants()
    {
        $url = 'https://api.openai.com/v1/assistants';
        $api_key = env('CHATGPT_API_KEY');

        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                    'OpenAI-Beta' => 'assistants=v2',
                ],
            ]);

            $data = json_decode($response->getBody(), true)['data'];

            return $data;
        } catch (\Exception $e) {
            // Handle exceptions (e.g., log the error)
            return [];
        }
    }

    
    function getAllAssistantsAndSyncAgents()
    {
        $url = 'https://api.openai.com/v1/assistants';
        $api_key = env('CHATGPT_API_KEY');

        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                    'OpenAI-Beta' => 'assistants=v2',
                ],
            ]);

            $assistantsData = json_decode($response->getBody(), true)['data'];

            // Update agents table
            foreach ($assistantsData as $assistant) {
                $formattedPromptJson = json_encode($formattedPrompt);
    
                Agent::updateOrCreate(
                    ['name' => $assistant['name']],
                    ['prompt' => $formattedPromptJson]
                );
            }

            return $assistantsData;
        } catch (\Exception $e) {
            // Handle exceptions (e.g., log the error)
            return [];
        }
    }


    function findAssistant($assistant_id)
    {
        $url = 'https://api.openai.com/v1/assistants/'.$assistant_id;
        $api_key = env('CHATGPT_API_KEY');
    
        // Specify the model you want to use
        $model = "gpt-3.5-turbo-1106"; // You can choose the model you want to use
        $data = [
            // Add your data here based on the OpenAI API requirements
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data), // Convert data to JSON format
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
                'OpenAI-Beta: assistants=v2'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
        curl_close($curl);
        return $response;
    }

    function addAssistant($name, $instructions, $model = null)
    {
        $url = 'https://api.openai.com/v1/assistants';
        $api_key = env('CHATGPT_API_KEY');
    
        // Specify the model you want to use
        $model = $model ? $model : "gpt-3.5-turbo-1106"; // You can choose the model you want to use

        $tools = [["type" => "code_interpreter"]];
        $data = [
            'instructions' => $instructions,
            'name' => $name,
            'model' => $model,
            'tools' => $tools
        ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data), // Convert data to JSON format
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
                'OpenAI-Beta: assistants=v2'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
    
        curl_close($curl);
    
        return $response;
    }


    function updateAssistant($assistant_id, $name, $instructions, $model = null)
    {
        $url = 'https://api.openai.com/v1/assistants/'.$assistant_id;
        $api_key = env('CHATGPT_API_KEY');
    
        // Specify the model you want to use
        $model = $model ? $model : "gpt-3.5-turbo-1106"; // You can choose the model you want to use
        // $model = "gpt-3.5-turbo-1106"; // You can choose the model you want to use
        $tools = [["type" => "code_interpreter"]];
        $data = [
            'instructions' => $instructions,
            'name' => $name,
            'model' => $model,
            'tools' => $tools
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data), // Convert data to JSON format
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
                'OpenAI-Beta: assistants=v2'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
        curl_close($curl);
        return $response;
    }


    function createThread()
    {
        $url = 'https://api.openai.com/v1/threads';
        $api_key = env('CHATGPT_API_KEY');
    
        $data = [
            // Add your data here based on the OpenAI API requirements
        ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data), // Convert data to JSON format
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
                'OpenAI-Beta: assistants=v2'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
    
        curl_close($curl);
    
        return $response;
    }

    public function addAssistantTask($assistant_id = null,$thread_id, $task)
    {
        $url = "https://api.openai.com/v1/threads/$thread_id/messages";
        $data = [
            "role" => "user",
            'content' => $task,
        ];
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . env('CHATGPT_API_KEY'),
                'OpenAI-Beta: assistants=v2',
            ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
    
        return $response;
    }
    

    public function runAssistantTask($assistant_id, $thread_id)
    {
        $url = "https://api.openai.com/v1/threads/$thread_id/runs";
        $data = [
            'assistant_id' => $assistant_id,
            'instructions' => '',
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data), // Use json_encode to create a valid JSON string
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . env('CHATGPT_API_KEY'),
                'OpenAI-Beta: assistants=v2',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    function checkMessageStatus($threadId, $runId)
    {
        $url = "https://api.openai.com/v1/threads/{$threadId}/runs/{$runId}";
        $api_key = env('CHATGPT_API_KEY');
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $api_key,
                'OpenAI-Beta: assistants=v2'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
    
        curl_close($curl);
    
        return $response;
    }


    function displayAssistantResponse($threadId)
    {
        $url = "https://api.openai.com/v1/threads/{$threadId}/messages";
        $api_key = env('CHATGPT_API_KEY');
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
                'OpenAI-Beta: assistants=v2'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
    
        curl_close($curl);
    
        return $response;
    }


    function generateAudio($voice, $input)
    {
        $url = 'https://api.openai.com/v1/audio/speech';
        $api_key = env('CHATGPT_API_KEY');
    
        // Specify the model you want to use
        $model = "tts-1"; // You can choose the model you want to use
    
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
                'OpenAI-Beta' => 'assistants=v2',
            ])
            ->post($url, [
                'input' => $input,
                'voice' => strtolower($voice),
                'model' => $model,
            ]);
    
        if ($response->successful()) {
            return $response->body();
        } else {
            // Handle error
            return 'Error: ' . $response->status() . ' - ' . $response->body();
        }
    }

    function generateImage($prompt, $n = 1, $size = '1024x1024', $model = null)
    {
        $url = 'https://api.openai.com/v1/images/generations';
        $api_key = env('CHATGPT_API_KEY');
    
        // Specify the model you want to use
        $model = $model ? $model : "tts-1"; // You can choose the model you want to use
        $data = [
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'n' => $n,
            'size' => $size,
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data), // Convert data to JSON format
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
                'OpenAI-Beta: assistants=v2'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
        curl_close($curl);
        return $response;
    }

    public function steps($runner_id, $runner_log_id)
    {
        $runner = ScenarioRunner::findOrFail($runner_id);
        $log = ScenarioRunnerLog::findOrFail($runner_log_id);

        switch ($log->status) {
            case ScenarioRunner::STATUS_NOT_STARTED:

                // return "Found #$runner_log_id Setting up..." .'<br>';
                if ($this->runs($log)) {
                    return "Job Done!".'<br>';
                } else {
                    return "Error while processing!".'<br>';
                }
                break;
            
            case ScenarioRunner::STATUS_RUNNING:
                return "Found #$runner_log_id Checking Run Status..." .'<br>';
                return $this->checks($runner, $log);
                break;
            case ScenarioRunner::STATUS_COMPLETED:
                // return "#$runner_log_id Finished" .'<br>';

                // return '<br> Input <br>';
                // return nl2br($log->input_content).'<br>';
                
                // return '<br> Output <br>';
                return true;
                return nl2br($log->output_content).'<br>';
                break;
        }

        return;
    }

    public function runs($log)
    {
        // try {
            $assistantId = $log->agent->gpt_code;
            $threadId = $log->thread_id;
            $task = $log->input_content;
        if ($task == null) {
            return "ERROR: Please add a task";
            return;
        }
            $payload = $log->payload;
    
            // Add Task
            $addTask = $this->addAssistantTask($assistantId, $threadId, $task);
            $payload['msg_id'] = json_decode($addTask, true)['id'];
    
            // Run Thread
            $runAssistant  = $this->runAssistantTask($assistantId, $threadId);
            $payload['run_id'] = json_decode($runAssistant, true)['id'];
    
            // Update Child Nodes
            $log->payload = json_encode($payload);
            $log->status = ScenarioRunnerLog::STATUS_RUNNING;
            $log->save();
            return "MsgID #".$payload['msg_id']." | RunID #".$payload['run_id']." </br>";
            return true;
        // } catch (\Throwable $th) {
        //     return false;
        // }
    }

    public function checks($runner, $log)
    {
        // try {
            $threadId = $log->thread_id;
            $task = $log->input_content;
        if ($task == null) {
            return "ERROR: Please add a task <br>";
            return;
        }
            $payload = $log->payload;
    
            $checkMessageStatus = $this->checkMessageStatus($threadId, json_decode($payload, true)['run_id']);

            $response = json_decode($checkMessageStatus, true);
        if ($response['status'] == "completed") {
            return "#".$log->id." - Status: Completed </br>";
        } else {
            return "#".$log->id." - Status: InProgress </br>";
            return;
        }
    
            $output =  $this->displayAssistantResponse($threadId);
            $responseMessages = json_decode($output, true);
            $message = $responseMessages['data'][0]['content'][0]['text']['value'];
    
            $log->update(['output_content' => $message,'status' => ScenarioRunner::STATUS_COMPLETED]);
            $nextstep = ScenarioRunnerLog::where('scenario_runner_id', $runner->id)->whereStatus(ScenarioRunnerLog::STATUS_NOT_STARTED)->first();
        if (!$nextstep) {
            $runner->update(['status' => ScenarioRunnerLog::STATUS_COMPLETED]);
        } else {
            $nextstep->update(['input_content' => $message]);
        }
            return;
        // } catch (\Throwable $th) {
        //     return false;
        // }
    }
    public function processLogs($scenario,$log){
        $output = $this->steps($scenario, $log->id);
        if($output != true){
            $this->processLogs($scenario,$log);
        }
    }


     //get result from api of GPT
    public function getOutputContent($prompt , $model = null,$imagePath)
    {
        $model = $model ? $model : "gpt-4-turbo"; // You can choose the model you want to use
        $response = Http::withToken(env('CHATGPT_API_KEY'))
        ->timeout(600)
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $prompt],
                        ['type' => 'image_url', 'image_url' => ['url' => $imagePath, 'detail' => 'low']]
                    ],
                ],
            ],
        ])
        ->throw()
        ->json();
        $output = $response['choices'][0]['message']['content'];
        return $output;
    }

    
    public function postContentByCurl($apiUrl, $data, $headers)
    {
        $ch = curl_init($apiUrl);
        $payload = json_encode($data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Disable SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if ($result === false) {
            // Handle cURL error
            return ['success' => false, 'error' => curl_error($ch)];
        }

        curl_close($ch);
        $response = json_decode($result, true);
        return $response;
    }
}

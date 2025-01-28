<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\MailSmsTemplate;
use App\Models\Folder;
use App\Models\ChartChunk;
trait GPTEncounterProcessor
{
    public function processExtractedContent($request){
        $folder = Folder::find($request->folder_id);
        $folderPrompt = MailSmsTemplate::where('id',$folder->prompt_id)->first();
        $content = $request->proceeded_content;
        $promptVariables = explode(',',$folderPrompt->variables[0]);
        $variables = '';
        foreach($promptVariables as $variable){
            $variables .= $variable .': ,'  ;
        }
        $prompt = $request->title .'</br> Reply with the following JSON:'. $variables;
        // $response = Http::withToken(env('CHATGPT_API_KEY'))
        // ->timeout(600) 
        // ->post('https://api.openai.com/v1/chat/completions', [
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => [[
        //         'role' => 'system',
        //         'content' => $prompt
        //     ], [
        //         'role' => 'user',
        //         'content' => $content,
        //     ]],
        // ])
        // ->throw()
        // ->json();
        $messages = [
            [
                'role' => 'system',
                'content' => $prompt,
            ],
            [
                'role' => 'user',
                'content' => $content,
            ],
        ];

         $response = Http::withToken(env('CHATGPT_API_KEY'))
        ->timeout(600)
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
        ])
        ->throw()
        ->json();
        $output = $response['choices'][0]['message']['content'];
        return $output; 
    }
   

    public function processMedicationContent($medicationContent){
        $instructions = [
            'key should contain the code of diagnosis do not provide array it should be in comma separated format only use provided content string to give output',
            'key should contain the code of diagnosis do not provide information in array. It should be in comma separated format only. Use provided content string in above given format',
            'key should contain the ICD10 codes of clinical diagnosis. Please do not provide array format. It should formatted by commas',
            'key has list of ICD10 codes i want you convert these codes to diagnosis. Do not provide array it should be in comma separated format only use provided content string to give output',
        ];
        
        
        $prompt = 'Please write the ICD10 CM codes for the mentioned medication(diagnoses) and provide them in JSON format. Treat each medication(diagnosis) after a comma (,) as a separate entry.

        The JSON format should be as follows:
        [
            {
                "medication": "",
                "icd_10_cm_code": ""
            },
            ...
        ]
        
        The "medication" key should contain the input medication(diagnoses), and the "icd_10_cm_code" key should contain the corresponding ICD-10 CM code. Do not provide an array; use a comma-separated format for the codes. Please provide the extracted diagnoses in this specified JSON format.';
        
        $response = Http::withToken(env('CHATGPT_API_KEY'))
         	->timeout(600) 
        	->post('https://api.openai.com/v1/chat/completions', [
              'model' => 'gpt-3.5-turbo',
              'messages' => [[
                  'role' => 'system',
                  'content' => $prompt
              ], [
                  'role' => 'user',
                  'content' => $medicationContent,
              ]],
          ])
          ->throw()
          ->json();
        $output = $response['choices'][0]['message']['content'];
        return $output;
    }


    public function processCoder($content){
        
        $prompt = 'Role: Senior Doctor & Medical Coding Specialist

                Task: Check given content and provide a ICD10 codes of medical conditions.

                Criteria:
                Given content check disease and provide ICD10 codes.
                There are multiple ICD10 codes on this given content.
                Avoid duplicate ICD10 codes.

                Output Format:
                [
                        {
                                "doctor_name": "",
                                "from_dos": "",
                                "location": "",
                                "findings": [
                                        {
                                                "disease_name": "",
                                                "icd10_code": "",
                                                "comment": "", // Remember to note the reference keyword so the coder can search it in text if needed.
                                        },
                                        ...
                                ]
                        },
                        ...
                ]

                Condition:
                If no relevant records found return "404" only.

                Note:
                Don’t write anything else.';
        $response = Http::withToken(env('CHATGPT_API_KEY'))
         	->timeout(600) 
        	->post('https://api.openai.com/v1/chat/completions', [
              'model' => 'gpt-4o',
              'messages' => [[
                  'role' => 'system',
                  'content' => $prompt
              ], [
                  'role' => 'user',
                  'content' => $content,
              ]],
          ])
          ->throw()
          ->json();
        $output = $response['choices'][0]['message']['content'];
        return $output;
    }

    public function processReviewer($content){
        
        $prompt = 'Role: Senior Doctor & Medical Coding Specialist

                Task: Verify and correct JSON content against the provided text content.

                Criteria:
                Thoroughly check each key in the JSON against the provided text content.
                Ensure all key information is accurate, verified, and properly aligned with the text content.

                Note: 
                The information is highly confidential and sensitive. Handle it with utmost care and attention.
                Pls follow Output conditions. Do not write anything else.

                Structure Definition:
                [
                        {
                                "doctor_name": // This is the name of the Doctor
                                "from_dos": // This is the date of Encounter (range between 2023 to 2024)
                                "location": // This is the name of the medical facility
                                "findings": [
                                        {
                                                "disease_name":
                                                "icd10_code":
                                                "comment": // Remember to note the reference headline name so the coder can recheck it if needed.
                                        },
                                        ...
                                ]
                        },
                        ...
                ]

                Output Conditions:
                If the provided JSON is correct and no changes needed return "200".
                If the provided JSON is invalid return "404".
                If corrections are possible, provide the corrected and accurate version of JSON only.';
        $response = Http::withToken(env('CHATGPT_API_KEY'))
         	->timeout(600) 
        	->post('https://api.openai.com/v1/chat/completions', [
              'model' => 'gpt-4o',
              'messages' => [[
                  'role' => 'system',
                  'content' => $prompt
              ], [
                  'role' => 'user',
                  'content' => $content,
              ]],
          ])
          ->throw()
          ->json();
        $output = $response['choices'][0]['message']['content'];
        return $output;
    }

    public function run($chart_chunk,$assistantId,$content,$run_id,$status,$thread_id = null)
    {
        
        $existingPayloadArray = $chart_chunk->payload;

        // If $existingPayloadArray is null, initialize as an empty array
        if (is_null($existingPayloadArray)) {
            $existingPayloadArray = [];
        }


        $assistantId = $assistantId;
        if($thread_id){
            $threadId = $thread_id;
        }else{
            $threadId = $chart_chunk->payload['thread_id'];
        }
        // Add Task
        $addTask = $this->addAssistantTask($assistantId, $threadId, $content);

        // Run Thread
        $runAssistant  = $this->runAssistantTask($assistantId, $threadId);
        $payload[$run_id] = json_decode($runAssistant, true)['id'];

        // Merge or append new data
        $newPayloadArray = $payload;
        $mergedPayloadArray = array_merge($existingPayloadArray, $newPayloadArray);

        $chart_chunk->payload = $mergedPayloadArray;
        $chart_chunk->status = $status;
        $chart_chunk->save();
        return true;
    }

    public function check($chart_chunk,$threadId,$runId,$status)
    {
        $checkMessageStatus = $this->checkMessageStatus($threadId, $runId);
        $response = json_decode($checkMessageStatus, true);
        if (isset($response['status']) && $response['status'] == "completed") {
        }else{
            return;
        }
    
        $output =  $this->displayAssistantResponse($threadId);
        $responseMessages = json_decode($output, true);
        $message = $responseMessages['data'][0]['content'][0]['text']['value'];
        $chart_chunk->result = $message;
        $chart_chunk->status = $status;
        $chart_chunk->save();
        return;
    }


    
    public function addAssistantTask($assistant_id, $thread_id, $task)
    {
        $url = "https://api.openai.com/v1/threads/$thread_id/messages";
        $data = [
            'role' => 'user',
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
            CURLOPT_POSTFIELDS => json_encode($data), // Use json_encode to create a valid JSON string
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . env('CHATGPT_API_KEY'),
                'OpenAI-Beta: assistants=v1',
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
                'OpenAI-Beta: assistants=v1',
            ),
        ));

        $response = curl_exec($curl);

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
                'OpenAI-Beta: assistants=v1'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
    
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
                'OpenAI-Beta: assistants=v1'
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
                'OpenAI-Beta: assistants=v1'
            ),
        ));
    
        $response = curl_exec($curl);
    
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
    
        curl_close($curl);
    
        return $response;
    }

}


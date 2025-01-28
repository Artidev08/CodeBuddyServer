<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\MailSmsTemplate;
use App\Models\Encounter;
use App\Models\Folder;
use Illuminate\Support\Facades\Http;
use App\Models\ProceededContent;
use Aws\S3\S3Client;

class ExtractorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logicalMethod(Request $request)
    {
        try{
            if ($request->text_extraction_mode =="Text") {
                dd($this->filterContent($request));     
            }elseif($request->text_extraction_mode == "Image"){
                if($request->encounter_type_file){
                    $output = $this->storeImage($request);
                }else{
                    return Response()->json([
                        "success" => false,
                        'message' => 'Error',
                        'title' => 'Please select Image to extract content!'
                    ]);
                }
            }elseif($request->text_extraction_mode == "Snipping"){
                $output = $this->storeSnippetImage($request);
            }else{
                if($request->encounter_type_pdf){
                    $output = $this->storePdf($request);
                }else{
                    return Response()->json([
                        "success" => false,
                        'message' => 'Error',
                        'title' => 'Please select PDF to extract content!'
                    ]);
                }
            }
            if(strlen($output) > 0){
                return Response()->json([
                    "success" => true,
                    "output" => $output,
                    'message' => 'Success',
                    'title' => 'Content Extracted Successfully!'
                ]);
            }else{
                return Response()->json([
                    "success" => false,
                    'message' => 'Error',
                    'title' => 'Sorry we are unable to extract content!'
                ]);
            }
            // return 's';
            // return redirect()->back()->with('success','Record Created');
        } catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
         * Processes requests based on different processing modes, such as GPT or AWS Comprehend Medical.
         *
         * @param Request $request The incoming request object containing the processing mode and other data.
         * @return JsonResponse Returns a JSON response based on the processing result.
    */
    public function processRequestBasedOnMode(Request $request)
    {
        try{
            if($request->processing_mode == null){
                return Response()->json([
                    "success" => false,
                    'message' => 'Error',
                    'title' => 'Please Select Mode!'
                ]);
            }
            // Retrieve the processing mode label from the request.
            $processingModeLabel = getProcessingModes($request->processing_mode)['label'];

            // Check if the processing mode is GPT (e.g., for generating text responses).
            if($processingModeLabel == 'GPT'){
                if(!$request->title){
                    return Response()->json([
                        "success" => false,
                        'message' => 'Error',
                        'title' => 'Please enter prompt!'
                    ]);
                }
                // Process the content using GPT-specific method.
                $output = $this->processExtractedContent($request);
                $parsedData = json_decode($output, true);
                if($parsedData == null){
                    $fieldNames = [];
                    if (preg_match_all('/"([^"]+)"\s*:\s*"[^"]+"/', $output, $fieldNames)) {
                        $fieldNames = $fieldNames[1];
                        $extractedData = [];
                        foreach ($fieldNames as $fieldName) {
                            $pattern = '/"' . preg_quote($fieldName) . '": "(.*?)"/';
                            if (preg_match($pattern, $output, $matches)) {
                                $extractedData[$fieldName] = $matches[1];
                            }
                        }
                        $jsonResult = json_encode($extractedData, JSON_PRETTY_PRINT);
                        $parsedData = json_decode($jsonResult, true);
                    }
                }
                $payload = [];
                $formattedData = '';
                $formattedOriginalData = '';
                $hcc = null;
                $medication = null;
                // if(isset($parsedData['diagnosis'])){
                //     $parsedData = $parsedData['diagnosis'];
                // }
                // if parsedData is not null then add encounters
                if(is_array($parsedData)){
                    foreach ($parsedData as $index => $data) {
                        if(is_array($data)){
                            foreach($data as $key => $value) {
                                if($key != '_token' && $key != 'folder_id' && $key != 'created_by') {
                                    $payload[$key] = $value;
                                }
                                $fValue = is_array($value) ? implode(',',$value) : $value;
                                $formattedData .= "$key: $fValue,<br>";
                                $formattedOriginalData .= "$fValue,";
                            }
                            // if (!empty($payload)) {
                                $request['payload'] = $payload;
                                $request['folder_id'] = $request->folder_id;
                                $request['hcc'] = $hcc;
                                $request['medication'] = $medication;
                                $encounter = Encounter::create($request->all());
                            // }
                        }else{
                            if($index != '_token' && $index != 'folder_id' && $index != 'created_by') {
                                $payload[$index] = $data;
                            }
                            $formattedData .= "$index: $data,<br>";
                            $formattedOriginalData .= "$data,";
                            $request['payload'] = $payload;
                            $request['folder_id'] = $request->folder_id;
                            $request['hcc'] = $hcc;
                            $request['medication'] = $medication;
                            $encounter = Encounter::create($request->all());
                        }
                    }
                }
            }
            // Check if the processing mode is the original Comprehend Medical Entities detection.
            elseif($processingModeLabel == 'detectEntities'){
                // Extract medical information using the original Comprehend method.
                $response = $this->extractMedicalInfo($request['proceeded_content']);
                $results = $this->getResultFormOutput($response);
            }
            // Check if the processing mode is Comprehend Medical Entities V2.
            elseif($processingModeLabel == 'detectEntitiesV2'){
                // Extract medical information using the updated Comprehend method V2.
                $response = $this->extractMedicalInfo($request['proceeded_content']);
                $results = $this->getResultFormOutput($response);
            }
            if($processingModeLabel != 'GPT'){
                // Assuming $results['diagnosis'] and $results['symptoms'] have been filled as per the previous examples:
                $formattedDiagnosis = isset($results['diagnosis']) ? implode(', ', $results['diagnosis']) : null;
                $formattedSymptoms = isset($results['symptoms']) ? implode(', ', $results['symptoms']) : null;

                // Prepend labels to each part
                $diagnosisText = $formattedDiagnosis ? "Diagnosis: - " . $formattedDiagnosis : '';
                $symptomsText = $formattedSymptoms ? "Symptoms: - " . $formattedSymptoms : '';

                // Combine both strings with a newline or any other separator as needed
                $formattedData = $diagnosisText . "<br>" . $symptomsText;
                $formattedOriginalData = implode(', ',  $results['diagnosis']);
                $request['payload'] = $results;
                $encounter = Encounter::create($request->all());
                $output = $formattedDiagnosis.''.$formattedSymptoms;
            }
            $formattedData = $formattedData ? $formattedData : json_encode($output);
            $formattedData .= "<hr class='bg-white'>";
            $formattedData = rtrim($formattedData, ",");
            $formattedOriginalData = rtrim($formattedOriginalData, ",");
            $encounters = Encounter::where('created_by',auth()->id())->where('folder_id', $request->folder_id)->latest()->paginate(10);
            $encountersCount = Encounter::where('created_by',auth()->id())->where('folder_id', $request->folder_id)->count();
            $folder = Folder::find($request->folder_id);
            //if output is not empty
            if(strlen($output) > 0){
                //start saving proceeded content
                $proceeded_content = New ProceededContent;
                $proceeded_content->user_id = auth()->id();
                $proceeded_content->folder_id = $request->folder_id;
                $proceeded_content->filtered_content = $request->proceeded_content;
                $proceeded_content->processed_content = $formattedOriginalData;
                $proceeded_content->save();
                // end saving proceeded content

                return Response()->json([
                    "success" => true,
                    "folder_id" => $request->folder_id,
                    'message' => 'Success',
                    "encountersCount" => $encountersCount,
                    "output" => $formattedData,
                    'originalOutput' => $formattedOriginalData,
                    'title' => 'Record Created Successfully!',
                    'output1' => $output
                ]);
            }else{
                return Response()->json([
                    "success" => false,
                    'message' => 'Error',
                    'title' => 'Sorry we are unable to process content!'
                ]);
            }
        } catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    function getResultFormOutput($output){
        $results = [
            'diagnosis' => [],
            'symptoms' => []
        ];
        foreach ($output as $item) {
            if ($item['Category'] === 'MEDICAL_CONDITION') {
                // Check if it has a DIAGNOSIS trait
                if ($this->array_some($item['Traits'], function ($trait) { return $trait['Name'] === 'DIAGNOSIS'; })) {
                    $results['diagnosis'][] = $item['Text'];
                }
                // Check if it has a SYMPTOM trait
                if ($this->array_some($item['Traits'], function ($trait) { return $trait['Name'] === 'SYMPTOM'; })) {
                    $results['symptoms'][] = $item['Text'];
                }
            }
        }
        return $results;
    }

    function array_some($array, $callback) {
        foreach ($array as $element) {
            if ($callback($element)) {
                return true;
            }
        }
        return false;
    }
    //Filter Extracted Content
    public function filterExtractedContent(Request $request)
    {
        try{
            $output = $this->filterContent($request); 
            if(strlen($output) > 0){
                return Response()->json([
                    "success" => true,
                    "output" => $output,
                    'message' => 'Success',
                    'title' => 'Content Filtered Successfully!'
                ]);
            }else{
                return Response()->json([
                    "success" => false,
                    'message' => 'Error',
                    'title' => 'Sorry we are unable to process content!'
                ]);
            }
        } catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    // Get HCC Data from CodeEasy By using HTTP request
    public function fetchMedicalData(Request $request)
    {
        try{
            $icd10code = $request->icd10code;
            if(!$icd10code){
                return Response()->json([
                    "success" => false,
                    'message' => 'Error',
                    'title' => 'Please enter ICD10 code!'
                ]);
            }
            $cleanedIcd10code = preg_replace('/\s+/', ' ', $icd10code);
            $cleanedIcd10code = preg_replace('/\./', '', $cleanedIcd10code);
            $formattedData = rtrim($cleanedIcd10code, ",");
            $icd10codeArray = array_unique(explode(',', $formattedData));
            $valueTableRows = '';
            $valueKey = 0;
            $nonIdentifiedKey = 0;
            $nonValueKey = 0;
            $nonValueTableRows = '';
            $nonIdentifiedTableRows = '';
            foreach ($icd10codeArray as $key => $value){
                //valueTableRows
                $valueResponse = Http::get('https://clinicalcodeeasy.com/get-information',['code' => $value,'hcc_type' => 'value']);
                $valueHtmlOutput = '';
                if($valueResponse->json()['hcc']){
                    $valueKey++;
                    foreach ($valueResponse->json()['hcc'] as $hccKey => $hccValue){
                        $valueHtmlOutput .= '<p class="m-0 mb-1"><strong>' . strtoupper($hccKey) . "</strong>: " . $hccValue . "</p>";
                    }
                    $diagnosis = $valueResponse->json()['sample_document'] ? $valueResponse->json()['sample_document'] : 'N/A';
                    $valueTableRow = "<tr>
                                <td> $valueKey</td>
                                <td> $value</td>
                                <td> $valueHtmlOutput</td>
                                <td> $diagnosis </td>
                            </tr>";
                    $valueTableRows .= $valueTableRow;
                }
                //nonValueTableRows
                $nonValueResponse = Http::get('https://clinicalcodeeasy.com/get-information',['code' => $value,'hcc_type' => 'non_value']);
                $nonValueHtmlOutput = '';
                if($nonValueResponse->json()['hcc']){
                    $nonValueKey++;
                    foreach ($nonValueResponse->json()['hcc'] as $hccKey => $hccValue){
                        $nonValueHtmlOutput .= '<p class="m-0 mb-1"><strong>' . strtoupper($hccKey) . "</strong>: " . $hccValue . "</p>";
                    }
                    $diagnosis = $nonValueResponse->json()['sample_document'] ? $nonValueResponse->json()['sample_document'] : 'N/A';
                    $nonValueTableRow = "<tr>
                                <td> $nonValueKey</td>
                                <td> $value</td>
                                <td> $nonValueHtmlOutput</td>
                                <td> $diagnosis</td>
                            </tr>";
                    $nonValueTableRows .= $nonValueTableRow;
                }

                //nonIdentifiedTableRows
                if((!$valueResponse->json()['hcc']) && (!$nonValueResponse->json()['hcc'])){
                    $nonIdentifiedKey++;
                    $nonIdentifiedTableRow = "<tr>
                                <td> $nonIdentifiedKey</td>
                                <td> $value</td>
                            </tr>";
                    $nonIdentifiedTableRows .= $nonIdentifiedTableRow;
                }
            }
            return Response()->json([
                "success" => true,
                'message' => 'Success',
                "value_output" => $valueTableRows,
                "value_count" => $valueKey,
                "non_value_output" => $nonValueTableRows,
                "non_value_count" => $nonValueKey,
                "non_identified_output" => $nonIdentifiedTableRows,
                "non_identified_count" => $nonIdentifiedKey,
            ]);
        } catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    // Get Diagnosis Data from CodeEasy By using HTTP request
    public function fetchDiagnosisData(Request $request)
    {
        try{
            $icd10code = $request->icd10code;
            if(!$icd10code){
                return Response()->json([
                    "success" => false,
                    'message' => 'Error',
                    'title' => 'Please enter ICD10 code!'
                ]);
            }
            $cleanedIcd10code = preg_replace('/\s+/', ' ', $icd10code);
            $cleanedIcd10code = preg_replace('/\./', '', $cleanedIcd10code);
            $formattedData = rtrim($cleanedIcd10code, ",");
            $icd10codeArray = array_unique(explode(',', $formattedData));
            $tableRows = '';
            $valueKey = 0;
            foreach ($icd10codeArray as $key => $value){
                $valueKey++;
                //tableRows
                $response = Http::get('https://clinicalcodeeasy.com/get-diagnosis',['code' => $value]);
                $description = $response->json()['description'] ? $response->json()['description'] : 'N/A';
                $tableRow = "<tr>
                                <td> $valueKey</td>
                                <td> $value</td>
                                <td> $description</td>
                            </tr>";
                $tableRows .= $tableRow;
            }
            return Response()->json([
                "success" => true,
                'message' => 'Success',
                "output" => $tableRows,
            ]);
        } catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }


     // Get HCC Data from CodeEasy By using HTTP request
     public function fetchMedicationData(Request $request)
     {
         try{
             $medication = $request->medication;
             if(!$medication){
                 return Response()->json([
                     "success" => false,
                     'message' => 'Error',
                     'title' => 'Please enter Medications!'
                 ]);
             }
            $tableRows = '';
            // $medicationArray = array_unique(explode(PHP_EOL, $medication));
            $cleanedMedication = preg_replace('/\s+/', ' ', $medication);
            $cleanedMedication = preg_replace('/\./', '', $cleanedMedication);
            $formattedData = rtrim($cleanedMedication, ",");
            $medicationArray = array_unique(explode(',', $formattedData));

            //get result from GPT
            $smartOutput = $this->processMedicationContent(implode(',',$medicationArray));
            $smartResultArray = json_decode($smartOutput, true);
            if(isset($smartResultArray['medication'])){
                $smartResultArray = $smartResultArray['medication'];
            }
            if(isset($smartResultArray['diagnoses'])){
                $smartResultArray = $smartResultArray['diagnoses'];
            }
            foreach ($medicationArray as $key => $value){
                $smartCode = '';
                $code = '<ul class="local_height">';
                $smartCodeValue = '<ul class="local_height">';
                if((isset($smartResultArray[$key]) && $smartResultArray[$key]['medication'])){
                    $smartCode = $smartResultArray[$key]['icd_10_cm_code'];
                }
                if($smartCode){
                    $smartCodes = explode(',', $smartCode);
                    foreach($smartCodes as $smartCode){
                        $smartCodeValue .= "<li>".$smartCode."</li>";
                    }
                }else{
                    $smartCodeValue = 'N/A';
                }
                $key++;
                $valueHtmlOutput= '';
                //tableRows
                $valueResponse = Http::get('https://clinicalcodeeasy.com/get-code',['medication' => $value]);

               
                if($valueResponse->json()){
                    $valueCode = $valueResponse->json();
                    // foreach($valueResponse->json() as $valueCode){
                        if($valueCode){
                            // $code .= "<li>".$valueCode['code']."</li>";
                            $code .= $valueCode['code'];
                            foreach ($valueCode['hcc'] as $hccKey => $hccValue){
                                $valueHtmlOutput .= '<p class="m-0 mb-1"><strong>' . strtoupper($hccKey) . "</strong>: " . $hccValue . "</p>";
                            }
                        }
                    // }
                }
                $code .= "</ul>";
                $smartCodeValue .= "</ul>";
                // $code = $valueResponse->json()['code'] ? $valueResponse->json()['code'] : 'N/A';
                $tableRow = "<tr>
                            <td> $key</td>
                            <td> $value</td>
                            <td> $code</td>
                            <td> $smartCodeValue</td>
                            <td> $valueHtmlOutput</td>
                        </tr>";
                $tableRows .= $tableRow;
            }
            return Response()->json([
                "success" => true,
                'message' => 'Success',
                "output" => $tableRows,
                "smartResultArray" => $smartOutput,
            ]);
         } catch(Exception $e){            
             return back()->with('error', 'There was an error: ' . $e->getMessage());
         }
     }

      // Get HCC Data from CodeEasy By using HTTP request
    public function fetchAdvanceMedicationData(Request $request)
    {
        try{
            $medication = $request->medication;
            if(!$medication){
                return Response()->json([
                    "success" => false,
                    'message' => 'Error',
                    'title' => 'Please enter Medications!'
                ]);
            }
            $tableRows = '';
            // $medicationArray = array_unique(explode(PHP_EOL, $medication));
            $cleanedMedication = preg_replace('/\s+/', ' ', $medication);
            $cleanedMedication = preg_replace('/\./', '', $cleanedMedication);
            $formattedData = rtrim($cleanedMedication, ",");
            $medicationArray = array_unique(explode(',', $formattedData));

            foreach ($medicationArray as $key => $value){
                $smartCode = '';
                $smartCodeValue = '<ul class="local_height">';
                $smartDescriptionValue = '<ul class="local_height">';
                $codes = '';
                $inferICD10CMs = $this->inferICD10CM($value);
                if($inferICD10CMs){
                    foreach ($inferICD10CMs as $k => $ICDcm){
                        $k++;
                        // $smartCodeValue .= "<li>".$ICDcm['Code']."</li>";
                        // $smartDescriptionValue .= "<li>".$ICDcm['Description']."</li>";
                        // $codes .= $ICDcm['Code'].",";
                        $tableRow = "<tr>
                            <td><input type='checkbox' class='smart_row' data-code='".$ICDcm['Code']."'> $k</td>
                            <td> $value</td>
                            <td>". $ICDcm['Description']."</td>
                            <td>". $ICDcm['Code']."</td>
                        </tr>";
                        $tableRows .= $tableRow;
                    }
                }else{
                    $smartCodeValue .= "N/A";
                    $smartDescriptionValue .= "N/A";
                }
               
                // $key++;
            
                // $smartCodeValue .= "</ul>";
                // $tableRow = "<tr>
                //             <td><input type='checkbox' class='smart_row' data-code='$codes'> $key</td>
                //             <td> $value</td>
                //             <td> $smartDescriptionValue</td>
                //             <td> $smartCodeValue</td>
                //         </tr>";
            }
            return Response()->json([
                "success" => true,
                'message' => 'Success',
                "output" => $tableRows,
            ]);
        } catch(Exception $e){            
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    


    public function hccConverter(Request $request){
        $data = $request->content;
        return view('member.tools.hcc',compact('data'));
    }

    public function diagnosisConverter(Request $request){
        $data = $request->content;
        return view('member.tools.diagnosis',compact('data'));
    }
    

    public function medicationConverter(Request $request){
        $data = $request->content;
        return view('member.converter.medication-icd10',compact('data'));
    }

    public function advanceMedicationConverter(Request $request){
        $data = $request->content;
        return view('member.converter.advance-medication-icd10',compact('data'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

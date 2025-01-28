<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202306-V1.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ProjectEntry;
use App\Models\ProceededContent;
use App\Models\Chart;
use App\Models\ChartChunk;
use App\Models\ChartDetail;
use App\Models\MedicalCondition;
use App\Jobs\ExtractPdfTextJob;
use App\Jobs\ChunkConvertorJob;
use App\Jobs\ChunkMedicalCoderJob;
use App\Jobs\ChunkEntrySyncJob;
use App\Jobs\SyncNativeICDJob;
use App\Jobs\SyncMedicalConditionJob;
use Illuminate\Support\Facades\Http;

class CronController extends Controller
{
    public function extractPdfText(){
        // every 15 min cron need to setup
        $charts = Chart::whereNull('extracted_text')
                ->where('is_extract',Chart::EXTRACT_PENDING)
                ->get();
                
        if ($charts->count() > 0) {
            // foreach ($charts as $chart) {
            //     $chart->is_extract = Chart::EXTRACT_SCHEDULED;
            //     $chart->save();
            // }
            // ExtractPdfTextJob::dispatch();
            foreach($charts as $chart) {
                // Attempt to extract text from the PDF associated with the chart
                $text = extractionTestFromConvertApi($chart->getFirstMedia('pdf')->getPath());
    
                // Clean and save the extracted text
                $chart->extracted_text = cleanString($text);
                $chart->is_extract = Chart::EXTRACT_COMPLETED;
                $chart->save();
                // Log successful extraction
                // \Log::info($chart->getPrefix() . " PDF extracted successfully");
            }
            $msg = $chart->count() . " chart is passed to extraction tool please wait little bit!";
        } else {
            $msg = "No charts are pending for extraction.";
        }
        updateCronJobTime('extract_pdf_cron_run_at');
        return $msg;
    } 


    // chunk converter
    public function chunkConvertor(){
        $charts = Chart::whereNotNull('extracted_text')
                ->where('workflow', 'automatic')
                ->where('is_extract', Chart::EXTRACT_COMPLETED)
                ->where('is_chunk', Chart::CHUNK_PENDING)
                ->get();
        $chunk_count = 0;
        if ($charts->count() > 0) {
            foreach($charts as $chart){
                $divide_logic = $chart->divide_criteria;
                $keyword = $divide_logic;
                $rawContent = $chart->extracted_text;
                $splitContent = splitContent($rawContent, $keyword);
    
                // Iterate over each part of the exploded text and create chart chunks
                foreach ($splitContent as $key => $content) {
                    ++$chunk_count;
                    $chartChunk = new ChartChunk;
                    $chartChunk->chart_id = $chart->id;
                    $chartChunk->sequence = $key + 1;
                    $chartChunk->content = $content;
                    $chartChunk->status = ChartChunk::STATUS_IN_QUEUE;
                    $chartChunk->save();
                }
    
                // Mark the chart as chunked
                $chart->is_chunk = Chart::CHUNK_COMPLETED;
                $chart->save();
    
                // \Log::info($chart->getPrefix() . " chart is processed with " . $chunk_count . " chunks created!");
            }
            // foreach ($charts as $chart) {
            //     $chart->is_chunk = Chart::CHUNK_SCHEDULED;
            //     $chart->save();
            // }
            // ChunkConvertorJob::dispatch();
        
            $msg = $charts->count() . " chart is passed to chunk converter please wait little bit!";
        } else {
            $msg = "No charts are pending for chunking.";
        }
        updateCronJobTime('chunk_converter_cron_run_at');
        return $msg;
    }
    

    // chunk medical coder
    public function chunkMedicalCoder(){
        $chart_chunks = ChartChunk::whereNotNull('content')
                ->whereIn('status', [ChartChunk::STATUS_IN_QUEUE])
                ->inRandomOrder()
                ->limit(1)
                ->get();
             
        if ($chart_chunks->count() > 0) {
            foreach ($chart_chunks as $chart_chunk){
                // Mark the chunk as processing
                $content = $chart_chunk->content;

                // Process the content with the coder
                $coderResult = processCoder($content);
                $chart_chunk->result = $coderResult;
                $chart_chunk->status = ChartChunk::STATUS_PROCESSING;
                $chart_chunk->save();

                $reviewerContent = 'Given Content: ' . $content . ' JSON: ' . $coderResult;

                if ($coderResult == '404') {
                    $reviewerResult = '404';
                } else {
                    // Process the content with the reviewer
                    $reviewerResult = processReviewer($reviewerContent, $coderResult);
                }

                // Save the reviewer's result
                $chart_chunk->reviewer_result = $reviewerResult;
                $chart_chunk->status = ChartChunk::STATUS_COMPLETED;
                $chart_chunk->save();

                // \Log::info($chart_chunk->getPrefix() . " chunk is processed to coder and reviewer!");
            }
            // ChunkMedicalCoderJob::dispatch();
        
            $msg = $chart_chunks->count() . " chunk is passed to coder and reviewer please wait little bit!";
        } else {
            $msg = "No chunk are pending for review.";
        }
        // Update the cron job's last run time
        updateCronJobTime('chunk_coder_cron_run_at');
        return $msg;
    }


    // chunk entry sync
    public function chunkEntrySync(){
        $chart_chunks = ChartChunk::whereNotNull('result')
                ->where('result', '!=','404')
                ->where('status', ChartChunk::STATUS_COMPLETED)
                ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_IN_QUEUE)
                ->inRandomOrder()
                ->get();
        $entry_count = 0;
        if ($chart_chunks->count() > 0) {
            foreach ($chart_chunks as $chart_chunk) {
                $result = $chart_chunk->result;

                if ($chart_chunk->reviewer_result != 200) {
                    $result = $chart_chunk->reviewer_result;
                }

                $jsonData = $result;
                $dataArray = json_decode($jsonData, true);

                if (is_array($dataArray)) {
                    foreach ($dataArray as $data) {
                        if (isset($data['findings']) && is_array($data['findings'])) {
                            foreach ($data['findings'] as $finding) {
                                ++$entry_count;
                                $entry = new ChartDetail;
                                $entry->sequence = $chart_chunk->sequence;
                                $entry->chunk_id = $chart_chunk->id;
                                $entry->chart_id = $chart_chunk->chart_id;
                                $entry->doctor_id = $data['doctor_name'];
                                $entry->from_dos = $data['from_dos'];
                                $entry->to_dos = $data['to_dos'];
                                $entry->institution = $data['location'];
                                $entry->medication = $finding['disease_name'];
                                $entry->dx = $finding['icd10_code'];
                                $entry->comments = $finding['comment'];
                                $entry->save();
                            }
                        } else {
                            if(isset($data['findings'])){
                                ++$entry_count;
                                $entry = new ChartDetail;
                                $entry->sequence = $chart_chunk->sequence;
                                $entry->chart_id = $chart_chunk->chart_id;
                                $entry->doctor_id = $data['doctor_name'];
                                $entry->from_dos = $data['from_dos'];
                                $entry->to_dos = $data['to_dos'];
                                $entry->institution = $data['location'];
                                $entry->dx = json_encode($data['findings']);
                                $entry->save();
                            }
                        }
                    }
                }

                $chart_chunk->entry_sync_status = ChartChunk::ENTRY_SYNC_STATUS_COMPLETED;
                $chart_chunk->save();

                // \Log::info($chart_chunk->getPrefix() . " chunk synced with " . $entry_count . " chart details!");
            } 
            // foreach ($chart_chunks as $chunks) {
            //     $chunks->entry_sync_status = ChartChunk::ENTRY_SYNC_STATUS_PROCESSING;
            //     $chunks->save();
            // }

            // ChunkEntrySyncJob::dispatch();
        
            $msg = $chart_chunks->count() . " chunk is passed to entry sync please wait little bit!";
        } else {
            $msg = "No chunk are pending for entry sync.";
        }

        // Update the cron job's last run time
        updateCronJobTime('chunk_entry_sync_cron_run_at');
        return $msg;
    }

    // chunk medical entry sync
    public function syncMedicalCondition(){
        $chart_chunks = ChartChunk::whereNotNull('result')
                ->where('result', '!=','404')
                ->where('status', ChartChunk::STATUS_COMPLETED)
                ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_COMPLETED)
                ->where('mc_sync_status', ChartChunk::MC_SYNC_STATUS_IN_QUEUE)
                ->inRandomOrder()
                ->get();
        $entry_count = 0;
        if ($chart_chunks->count() > 0) {
            foreach ($chart_chunks as $chart_chunk) {
                if ($chart_chunk) {
                    $content = $chart_chunk->content;
                    $medicalConditions = MedicalCondition::select('id', 'title', 'code')->get();

                    foreach ($medicalConditions as $medicalCondition) {
                        $is_matched = isExactMatchCaseInsensitive($content,$medicalCondition->title);
                        if ($is_matched !== false) {
                            $chartDetail = ChartDetail::where('medication', $medicalCondition->title)->first();

                            if ($chartDetail == null) {
                                ++$entry_count;
                                $entry = new ChartDetail;
                                $entry->chunk_id = $chart_chunk->id;
                                $entry->chart_id = $chart_chunk->chart_id;
                                $entry->medication = $medicalCondition->title;
                                $entry->dx = 'N/A';
                                $entry->native_dx = $medicalCondition->code;
                                $entry->save();
                            }
                        }
                    }

                    // Mark the chunk as closed
                    $chart_chunk->mc_sync_status = ChartChunk::MC_SYNC_STATUS_COMPLETED;
                    $chart_chunk->save();

                    // \Log::info($chart_chunk->getPrefix() . " chunk synced with " . $entry_count . " chart details!");

                } 
            }
            // foreach ($chart_chunks as $chunks) {
            //     $chunks->mc_sync_status = ChartChunk::MC_SYNC_STATUS_PROCESSING;
            //     $chunks->save();
            // }
            // // Dispatch the job to handle the sync
            // SyncMedicalConditionJob::dispatch();
            $msg = $chart_chunks->count()  . " chunk is passed to medical entry sync please wait little bit!";
        } else {
            $msg = "No chunk is pending for medical entry sync.";
        }
    
        // Update the cron job's last run time
        updateCronJobTime('sync_medical_condition_cron_run_at');
        return $msg;
    }
    
    // chunk entry sync
    public function syncNativeICDSync(){
        $details = ChartDetail::whereNotNull('medication')
                ->whereNull('native_dx')
                ->get();

        $loop_iterate = 0;
        $logic_count = 0;

        if ($details->count() > 0) {
            foreach ($details as $detail) {
                ++$loop_iterate;
                $medicalCondition = MedicalCondition::where('title', $detail->medication)->first();
                $detail->native_dx = $medicalCondition ? $medicalCondition->code : 'N/A';
                $detail->save();
                ++$logic_count;
            }
            // SyncNativeICDJob::dispatch($details);
            $msg = $details->count() . " chart details is passed to sync native DX please wait little bit!";
        } else {
            $msg = "No chart details are pending for native DX sync.";
        }

        // Update the cron job's last run time
        updateCronJobTime('sync_native_icd_cron_run_at');
        return $msg;
    }

   


    public function clearProceededContent(){
        $content = ProceededContent::query()->delete();
        updateCronJobTime('clear_proceeded_content_cron_run_at');
        return "Content cleared successfully";
    } 

    // remove 7 days older records
    public function clearProjectEntry(){
        $sevenDaysAgo = now()->subDays(7);

        // Delete records older than 7 days
        $deletedCount = ProjectEntry::where('created_at', '<', $sevenDaysAgo)->delete();
        updateCronJobTime('clear_proceeded_entity_cron_run_at');
        return "Deleted $deletedCount entries that are older than 7 days.";
    } 

}

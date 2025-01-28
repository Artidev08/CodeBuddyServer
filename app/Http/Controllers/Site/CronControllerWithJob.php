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
use App\Jobs\ExtractPdfToText;
use App\Jobs\ChunkConvertorJob;
use App\Jobs\ChunkMedicalCoderJob;
use App\Jobs\ChunkEntrySyncJob;
use App\Jobs\SyncNativeICDJob;
use App\Jobs\SyncMedicalConditionJob;
use App\Jobs\SyncLocationJob;
use App\Jobs\SyncRecordTypeJob;
use App\Jobs\SyncMedicalHCCFinderJob;

class CronControllerWithJob extends Controller
{
    public function extractPdfText(){
        $charts = Chart::where('is_extract',Chart::EXTRACT_PENDING)
                ->get();
                
        if ($charts->count() > 0) {
            foreach ($charts as $chart) {
                $chart->is_extract = Chart::EXTRACT_SCHEDULED;
                $chart->save();
                ExtractPdfToText::dispatch($chart);
            }
            $msg = $charts->count() . " chart is passed to extraction tool please wait little bit!";
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
        if ($charts->count() > 0) {
            
            foreach ($charts as $chart) {
                $chart->is_chunk = Chart::CHUNK_SCHEDULED;
                $chart->save();
                ChunkConvertorJob::dispatch($chart);
            }
        
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
                ->where('status', ChartChunk::STATUS_IN_QUEUE)
                ->get();
             
        if ($chart_chunks->count() > 0) {
            foreach ($chart_chunks as $chunk) {
                $chunk->status = ChartChunk::STATUS_PROCESSING;
                $chunk->save();
                ChunkMedicalCoderJob::dispatch($chunk);
            }
        
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
                // ->where('result', '!=','404')
                ->where('status', ChartChunk::STATUS_COMPLETED)
                ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_IN_QUEUE)
                ->inRandomOrder()
                ->get();
        if ($chart_chunks->count() > 0) {
           
            foreach ($chart_chunks as $chunks) {
                $chunks->entry_sync_status = ChartChunk::ENTRY_SYNC_STATUS_PROCESSING;
                $chunks->save();
                ChunkEntrySyncJob::dispatch($chunks);
            }

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
                // ->where('result', '!=','404')
                ->where('status', ChartChunk::STATUS_COMPLETED)
                ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_COMPLETED)
                ->where('mc_sync_status', ChartChunk::MC_SYNC_STATUS_IN_QUEUE)
                ->inRandomOrder()
                ->get();
        if ($chart_chunks->count() > 0) {
            
            foreach ($chart_chunks as $chunks) {
                $chunks->mc_sync_status = ChartChunk::MC_SYNC_STATUS_PROCESSING;
                $chunks->save();
                SyncMedicalConditionJob::dispatch($chunks);
            }
            // Dispatch the job to handle the sync
            $msg = $chart_chunks->count()  . " chunk is passed to medical entry sync please wait little bit!";
        } else {
            $msg = "No chunk is pending for medical entry sync.";
        }
    
        // Update the cron job's last run time
        updateCronJobTime('sync_medical_condition_cron_run_at');
        return $msg;
    }

    public function medicalHCCFinder(){
        $chart_chunks = ChartChunk::whereNotNull('result')
            // ->where('result', '!=','404')
            ->where('status', ChartChunk::STATUS_COMPLETED)
            ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_COMPLETED)
            ->where('mc_sync_status', ChartChunk::MC_SYNC_STATUS_COMPLETED)
            ->where('hcc_sync_status', ChartChunk::HCC_SYNC_STATUS_IN_QUEUE)
            ->get();
        if ($chart_chunks->count() > 0) {
            
            foreach ($chart_chunks as $chunks) {
                $chunks->hcc_sync_status = ChartChunk::HCC_SYNC_STATUS_PROCESSING;
                $chunks->save();
                SyncMedicalHCCFinderJob::dispatch($chunks);
            }
            // Dispatch the job to handle the sync
            $msg = $chart_chunks->count()  . " details is passed to hcc sync please wait little bit!";
        } else {
            $msg = "No details is pending for hcc sync.";
        }

        // Update the cron job's last run time
        updateCronJobTime('sync_native_icd_cron_run_at');
        return $msg;
    }

    // chunk entry sync
    public function syncNativeICDSync(){
        $chart_chunks = ChartChunk::whereNotNull('result')
            // ->where('result', '!=','404')
            ->where('status', ChartChunk::STATUS_COMPLETED)
            ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_COMPLETED)
            ->where('mc_sync_status', ChartChunk::MC_SYNC_STATUS_COMPLETED)
            ->where('hcc_sync_status', ChartChunk::HCC_SYNC_STATUS_COMPLETED)
            ->where('native_sync_status', ChartChunk::NATIVE_SYNC_STATUS_IN_QUEUE)
            ->get();

        if ($chart_chunks->count() > 0) {
            
            foreach ($chart_chunks as $chunks) {
                $chunks->native_sync_status = ChartChunk::NATIVE_SYNC_STATUS_PROCESSING;
                $chunks->save();
                SyncNativeICDJob::dispatch($chunks);
            }
            // Dispatch the job to handle the sync
            $msg = $chart_chunks->count()  . " chunk is passed to native dx sync please wait little bit!";
        } else {
            $msg = "No chunk is pending for native dx sync.";
        }

        // Update the cron job's last run time
        updateCronJobTime('sync_native_icd_cron_run_at');
        return $msg;
    }
    


    // chunk entry sync
    public function syncLocation(){
        $chart_chunks = ChartChunk::whereNotNull('result')
            // ->where('result', '!=','404')
            ->where('status', ChartChunk::STATUS_COMPLETED)
            ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_COMPLETED)
            ->where('mc_sync_status', ChartChunk::MC_SYNC_STATUS_COMPLETED)
            ->where('native_sync_status', ChartChunk::NATIVE_SYNC_STATUS_COMPLETED)
            ->where('location_sync_status', ChartChunk::LOCATION_SYNC_STATUS_IN_QUEUE)
            ->get();

        if ($chart_chunks->count() > 0) {
            
            foreach ($chart_chunks as $chunks) {
                $chunks->location_sync_status = ChartChunk::LOCATION_SYNC_STATUS_PROCESSING;
                $chunks->save();
                SyncLocationJob::dispatch($chunks);
            }
            // Dispatch the job to handle the sync
            $msg = $chart_chunks->count()  . " chunk is passed to location sync please wait little bit!";
        } else {
            $msg = "No chunk is pending for location sync.";
        }


        // Update the cron job's last run time
        updateCronJobTime('sync_location_cron_run_at');
        return $msg;
    }

    
    // chunk entry sync
    public function syncRecordType(){
        $chart_chunks = ChartChunk::whereNotNull('result')
            // ->where('result', '!=','404')
            ->where('status', ChartChunk::STATUS_COMPLETED)
            ->where('entry_sync_status', ChartChunk::ENTRY_SYNC_STATUS_COMPLETED)
            ->where('mc_sync_status', ChartChunk::MC_SYNC_STATUS_COMPLETED)
            ->where('native_sync_status', ChartChunk::NATIVE_SYNC_STATUS_COMPLETED)
            ->where('location_sync_status', ChartChunk::LOCATION_SYNC_STATUS_COMPLETED)
            ->where('rt_sync_status', ChartChunk::RT_SYNC_STATUS_IN_QUEUE)
            ->get();

        if ($chart_chunks->count() > 0) {
            
            foreach ($chart_chunks as $chunks) {
                $chunks->rt_sync_status = ChartChunk::RT_SYNC_STATUS_PROCESSING;
                $chunks->save();
                SyncRecordTypeJob::dispatch($chunks);
            }
            // Dispatch the job to handle the sync
            $msg = $chart_chunks->count()  . " chunk is passed to record type sync please wait little bit!";
        } else {
            $msg = "No chunk is pending for record type sync.";
        }

        // Update the cron job's last run time
        updateCronJobTime('sync_record_type_cron_run_at');
        return $msg;
    }

    // cron for unlink chart pdf files
    public function unlinkProcessedChartPdf(){
        // Get the timestamp for 24 hours ago
        $twentyFourHoursAgo = now()->subHours(24);
        $iterate = 0;
        $logic_count = 0;
        
        // Retrieve charts that are older than 24 hours and have associated media
        $charts = Chart::where('created_at', '<', $twentyFourHoursAgo)->whereHas('media')->get();
        foreach ($charts as $chart) {
            ++$iterate;
            $filePath = $chart->getFirstMedia('pdf')->getPath();
            
            // Attempt to unlink the PDF file
            if (unlink($filePath)) {
                ++$logic_count;
                $chart->clearMediaCollection('pdf'); // Clear the associated media collection
            }
        }
        
        // Update the cron job time after processing
        updateCronJobTime('unlink_processed_chart_pdf_cron_run_at');
        
        return "Loop iterated $iterate times with $logic_count media unlinked.";
    }
    

    // cron for ignore unwanted medications
    public function ignoreMedications(){
        // Get the timestamp for 24 hours ago
        $iterate = 0;
        $logic_count = 0;
        
       
        
        // Update the cron job time after processing
        updateCronJobTime('ignore_medications_cron_run_at');
        
        return "Loop iterated $iterate times with $logic_count medication removed.";
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

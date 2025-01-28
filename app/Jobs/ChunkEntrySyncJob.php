<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChartChunk;
use App\Models\ChartDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ChunkEntrySyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $chunk;
    /**
     * Create a new job instance.
     *
     * @param \App\Models\Chunk $chunk
     * @return void
     */
    public function __construct($chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $entry_count = 0;

        try {
            $chart_chunk = $this->chunk;
            Log::info("chunk entry sync cron executing...");

            $result = $chart_chunk->result;

            // Process the content with the entry
            if($chart_chunk->entry_sync_status == ChartChunk::ENTRY_SYNC_STATUS_COMPLETED){
            Log::info("chunk entry sync is already completed...");
                return true;
            }
            Log::info("chunk entry sync is passed ahead...");



            if ($chart_chunk->reviewer_result != 200) {
                $result = $chart_chunk->reviewer_result;
            }

            $jsonData = $result;
            $dataArray = json_decode($jsonData, true);

            $chart = $chart_chunk->chart;
            $project = isset($chart->project) ? $chart->project : null;
            $keywords = null;
            if($project){
                $keywords =  $project->keywords ? explode(',',@$project->keywords[0]) : null;
            }

            if (is_array($dataArray)) {
                foreach ($dataArray as $data) {
                    if (isset($data['findings']) && is_array($data['findings'])) {
                        foreach ($data['findings'] as $finding) {
                            $disease_name= $finding['disease_name'];
                            $exist = ChartDetail::where('chunk_id',$chart_chunk->id)->where('medication',$disease_name)->first();
                            if($exist == null){
                                $entry = true;
                                if($keywords && is_array($keywords)){
                                    if(in_array($disease_name,$keywords)){
                                        $entry = false;
                                    }
                                }
                                if($entry == true){
                                    ++$entry_count;
                                    $entry = new ChartDetail;
                                    $entry->sequence = $chart_chunk->sequence ?? 'N/A';
                                    $entry->chunk_id = $chart_chunk->id ?? 'N/A';
                                    $entry->chart_id = $chart_chunk->chart_id ?? 'N/A';
                                    $entry->doctor_id = $data['doctor_name'] ?? 'N/A';
                                    $entry->bmi = $data['bmi'] ?? 'N/A';
                                    $entry->from_dos = date('m/d/Y', strtotime($data['from_dos'])) ?? 'N/A';
                                    $entry->to_dos = date('m/d/Y', strtotime($data['to_dos'])) ?? 'N/A';
                                    $entry->institution = $data['location'] ?? 'N/A';
                                    $entry->medication = $finding['disease_name'] ?? 'N/A';
                                    $entry->dx = $finding['icd10_code'] ?? 'N/A';
                                    $entry->comments = $finding['comment'] ?? 'N/A';
                                    $entry->save();
                                }
                            }
                        }
                    } else {
                        if(isset($data['findings'])){
                            ++$entry_count;
                            $entry = new ChartDetail;
                            $entry->sequence = $chart_chunk->sequence ?? 'N/A';
                            $entry->chart_id = $chart_chunk->chart_id ?? 'N/A';
                            $entry->doctor_id = $data['doctor_name'] ?? 'N/A';
                            $entry->bmi = $data['bmi'] ?? 'N/A';
                            $entry->from_dos = $data['from_dos'] ?? 'N/A';
                            $entry->to_dos = $data['to_dos'] ?? 'N/A';
                            $entry->institution = $data['location'] ?? 'N/A';
                            $entry->dx = json_encode($data['findings']) ?? 'N/A';
                            $entry->save();
                        }
                    }
                }
            }

            if($entry_count == 0){
                $chart_chunk->mc_sync_status = ChartChunk::MC_SYNC_STATUS_COMPLETED;
                $chart_chunk->hcc_sync_status = ChartChunk::HCC_SYNC_STATUS_COMPLETED;
                $chart_chunk->native_sync_status = ChartChunk::NATIVE_SYNC_STATUS_COMPLETED;
                $chart_chunk->location_sync_status = ChartChunk::LOCATION_SYNC_STATUS_COMPLETED;
                $chart_chunk->rt_sync_status = ChartChunk::RT_SYNC_STATUS_COMPLETED;
            }
            $chart_chunk->entry_sync_status = ChartChunk::ENTRY_SYNC_STATUS_COMPLETED;
            $chart_chunk->save();

            Log::info($chart_chunk->getPrefix() . " chunk synced with " . $entry_count . " chart details!");
            
            // call the next cron
            // $response = Http::get(url('cron/native-icd-sync/UHGT5437'));
            // Log::info($chart->getPrefix() . "Native DX sync cron started successfully");

            // sync cron
            syncCrons();
            Log::info($chart_chunk->getPrefix() . " passed for sync cron executed");
        } catch (Exception $e) {
            // Handle any exceptions that occur during the sync process
            Log::error('Chunk entry sync failed for chart chunk ID ' . $this->chunk->id . ': ' . $e->getMessage());
        }
    }
}
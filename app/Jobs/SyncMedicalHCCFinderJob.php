<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChartDetail;
use App\Models\MedicalCondition;
use App\Models\ChartChunk;
use Illuminate\Support\Facades\Log;
use Exception;

class SyncMedicalHCCFinderJob implements ShouldQueue
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
        try {
            $chart_chunk = $this->chunk;
            Log::info("chunk hcc sync executing...");

            $medications = ChartDetail::whereNotNull('medication')
            ->where('chunk_id', $chart_chunk->id)
            ->pluck('medication')
            ->toArray();

            if(count($medications) > 0) {
                // Implode the array into a string with each medication on a new line
                $content = implode("\n", $medications);
    
                // Process the content with the coder
                if($chart_chunk->hcc_sync_status == ChartChunk::HCC_SYNC_STATUS_COMPLETED){
                    Log::info("chunk hcc sync already completed...");
                    return true;
                }
                Log::info("chunk hcc sync is passed to hcc finder for execution...");
    
                $finderResult = processHCCFinder($content);

                $chart_chunk->hcc_finder_result = $finderResult;
                $chart_chunk->hcc_sync_status = ChartChunk::HCC_SYNC_STATUS_COMPLETED;
                $chart_chunk->save();

                if ($chart_chunk->hcc_finder_result != '404') {
                    $jsonData = $chart_chunk->hcc_finder_result;
                    $dataArray = json_decode($jsonData, true);
                    if (is_array($dataArray)) {
                        foreach ($dataArray as $data) {
                            $detail = ChartDetail::where('chunk_id', $chart_chunk->id)->where('medication',$data['diagnosis'])->first();
                            if($detail){
                                $hcc =  [
                                    'rx' => isset($data['rx']) ?$data['rx'] : null,
                                    'cms' => isset($data['cms']) ?$data['cms'] : null,
                                    'esrd' => isset($data['esrd']) ?$data['esrd'] : null,
                                ];
                                $detail->hcc = $hcc;
                                $detail->save();
                            }
                        }
                    }
                }
    
                Log::info($chart_chunk->getPrefix() . " chunk hcc sync executed...!");
                // sync cron
                syncCrons();
                Log::info($chart_chunk->getPrefix() . " passed for sync cron executed");
            }else{
                $chart_chunk->hcc_finder_result = null;
                $chart_chunk->hcc_sync_status = ChartChunk::HCC_SYNC_STATUS_COMPLETED;
                $chart_chunk->save();
                Log::info($chart_chunk->getPrefix() . " chart details is empty...!");
            }

        } catch (Exception $e) {
            // Log the error message
            $this->chunk->hcc_sync_status = ChartChunk::HCC_SYNC_STATUS_FAILED;
            $this->chunk->save();
            Log::error('Processing failed for chart chunk ID'. $this->chunk->id.': ' . $e->getMessage());
        }
    }
}
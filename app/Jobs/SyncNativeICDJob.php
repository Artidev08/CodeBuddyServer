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

class SyncNativeICDJob implements ShouldQueue
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

            Log::info($chart_chunk->getPrefix() . " sync native dx and hcc cron executing...");
           
            if ($chart_chunk) {
                $details = ChartDetail::whereNotNull('medication')
                        ->where('chunk_id',$chart_chunk->id)
                        ->get();

                foreach ($details as $detail) {
                    // Check if the $content matches the $medicalCondition title (case-insensitive)
                    $medicalCondition = MedicalCondition::where('title', $detail->medication)->first();
                    $hcc = null;
                    if($medicalCondition){
                        $hcc =  [
                            'rx' => $medicalCondition->hcc ? $medicalCondition->hcc->rx : null,
                            'cms' => $medicalCondition->hcc ? $medicalCondition->hcc->cms : null,
                            'esrd' => $medicalCondition->hcc ? $medicalCondition->hcc->esrd : null,
                        ];
                    }
                    
                    $detail->native_dx = $medicalCondition ? $medicalCondition->code : 'N/A';
                    $detail->native_hcc = $hcc ? $hcc : null;
                    $detail->save();
                    Log::info($detail->getPrefix() . " chart detail native dx and hcc synced!");
                }
                
                // Mark the chunk as completed
                $chart_chunk->native_sync_status = ChartChunk::NATIVE_SYNC_STATUS_COMPLETED;
                $chart_chunk->save();

                Log::info($chart_chunk->getPrefix() . " sync native dx and hcc cron executed");
                // sync cron
                syncCrons();
                Log::info($chart_chunk->getPrefix() . " passed for sync cron executed");
            } 
        } catch (Exception $e) {
            Log::error('SyncMedicalConditionJob failed for chart chunk ID ' . $this->chunk->id . ': ' . $e->getMessage());
        }
    }
}
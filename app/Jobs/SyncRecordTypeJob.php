<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChartDetail;
use App\Models\ChartChunk;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncRecordTypeJob implements ShouldQueue
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

            Log::info($chart_chunk->getPrefix() . " sync record type cron executing...");
           
            $details = ChartDetail::whereNotNull('medication')
                    ->where('chunk_id',$chart_chunk->id)
                    ->get();
            $chunk_content = $chart_chunk->content;
            $record_type = null;
            if($chunk_content != null){
                $record_type = identifyRecordType($chunk_content);
            }
            foreach ($details as $detail) {
                $detail->record_type = $record_type ? $record_type : 'N/A';
                $detail->save();
                Log::info($detail->getPrefix() ." Record Type synced");
            }

            // Mark the chunk as completed
            $chart_chunk->rt_sync_status = ChartChunk::RT_SYNC_STATUS_COMPLETED;
            $chart_chunk->save();

            Log::info($chart_chunk->getPrefix() . " sync record type cron executed");
            // sync cron
            syncCrons();
            Log::info($chart_chunk->getPrefix() . " passed for sync cron executed");
        } catch (Exception $e) {
            Log::error('Processing failed for chart detail ID'. $this->chunk->id. $e->getMessage());
        }
    }
}

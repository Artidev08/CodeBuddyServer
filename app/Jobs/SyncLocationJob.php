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
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncLocationJob implements ShouldQueue
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

            Log::info($chart_chunk->getPrefix() . " sync location cron executing...");
           
            if ($chart_chunk) {
                $details = ChartDetail::whereNotNull('medication')
                        ->where('chunk_id',$chart_chunk->id)
                        ->get();
                $chunk_content = $chart_chunk->content;
                $location = null;

                if($chunk_content != null){

                    $orgDirPath = storage_path('app/public/json/org-directories.json');
                    $orgs = json_decode(file_get_contents($orgDirPath));
    
                    foreach($orgs as $org){
                        if(searchStringInsensitive($chunk_content,$org)){
                            $location = $org; 
                            break;
                        }
                    }
                }

                foreach ($details as $detail) {

                    if($chunk_content != null){
                        $detail->location = $location ? $location : 'N/A';
                        $detail->save();
                        Log::info($detail->getPrefix() ." Location sync cron executed...");
                    }else{
                        Log::info($detail->getPrefix() .' Chunk Content is Empty');
                    }
                }

                // Mark the chunk as completed
                $chart_chunk->location_sync_status = ChartChunk::LOCATION_SYNC_STATUS_COMPLETED;
                $chart_chunk->save();

                Log::info($chart_chunk->getPrefix() . " sync location cron executed");
                // sync cron
                syncCrons();
                Log::info($chart_chunk->getPrefix() . " passed for sync cron executed");
            }else{
               Log::info($chart_chunk->getPrefix() .' Chunk not found');
            }

        } catch (Exception $e) {
           Log::error('Processing failed for chart detail ID'. $this->chunk->id. $e->getMessage());
        }
    }
}


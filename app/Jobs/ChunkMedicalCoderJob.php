<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ChartChunk;
use App\Models\Chart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ChunkMedicalCoderJob implements ShouldQueue
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
            Log::info("chunk medical coder executing...");

            $content = $chart_chunk->content;

            // Process the content with the coder
            if($chart_chunk->status == ChartChunk::STATUS_COMPLETED){
                Log::info("chunk medical coder already completed...");
                return true;
            }
            Log::info("chunk medical coder is passed to coder for execution...");

            $coderResult = processCoder($content);
            $chart_chunk->result = $coderResult;
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

            Log::info($chart_chunk->getPrefix() . " chunk medical coder executed...!");


            // call the next cron
            // $response = Http::get(url('cron/chunk-entry-sync/BHYT6543'));
            // Log::info($chart->getPrefix() . "Chunk Entry sync cron started successfully");

            // sync cron
            syncCrons();
            Log::info($chart_chunk->getPrefix() . " passed for sync cron executed");
        } catch (Exception $e) {
            // Log the error message
            $this->chunk->status = ChartChunk::STATUS_FAILED;
            $this->chunk->save();
            Log::error('Processing failed for chart chunk ID'. $this->chunk->id.': ' . $e->getMessage());
        }
    }
}

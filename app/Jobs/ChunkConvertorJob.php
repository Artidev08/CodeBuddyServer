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

class ChunkConvertorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chart;
    /**
     * Create a new job instance.
     *
     * @param \App\Models\Chart $chart
     * @return void
     */
    public function __construct($chart)
    {
        $this->chart = $chart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $chart = $this->chart;
            Log::info($chart->getPrefix() ." chunk converting..");

            $chunk_count = 0;
            
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

            Log::info($chart->getPrefix() . " chart is converted with " . $chunk_count . " chunks created!");

            
            // sync cron
            syncCrons();
            Log::info($chart->getPrefix() . " passed for sync cron executed");
        } catch (Exception $e) {
            
            $this->chart->is_chunk = Chart::CHUNK_FAILED;
            $this->chart->save();

            // Log the error message or take any other necessary actions
           Log::error('Processing failed for chart ID'. $this->chart->id. $e->getMessage());
        }
    }
}

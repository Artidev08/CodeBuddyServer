<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Chart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExtractPdfToText implements ShouldQueue
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
        // try {
            $chart = $this->chart;
            
            Log::info($chart->getPrefix(). ' Chart Extracting...');
            $filePath = $chart->getFirstMedia('pdf')->getPath();

            if (is_readable($filePath)) {
            
                // Attempt to extract text from the PDF associated with the chart
                $text = $this->extractionFromConvertApi($chart->getFirstMedia('pdf')->getPath());

                // Clean and save the extracted text
                $chart->extracted_text = cleanString($text);
                $chart->is_extract = Chart::EXTRACT_COMPLETED;
                $chart->save();
                // Log successful extraction
                Log::info($chart->getPrefix() . "Chart extracted successfully");
                
                // call the next cron
                // $response = Http::get(url('cron/chunk-convertor/GTFD3421'));
                // Log::info($chart->getPrefix() . "Chunk Convert cron started successfully");
                
                // sync cron
                syncCrons();
                Log::info($chart->getPrefix() . " passed for sync cron executed");
            } else {
                Log::info($chart->getPrefix() . " File is not readable");
            }
        // } catch (\Exception $e) {

        //     // Log the error message or take any other necessary actions
        //     $this->chart->is_extract = Chart::EXTRACT_FAILED;
        //     $this->chart->save();
        //     Log::error('PDF extraction failed for chart ID ' .$this->chart->id. $e->getMessage());
        // }
    }
    function extractionFromConvertApi($pdfPath){
        \ConvertApi\ConvertApi::setApiSecret('secret_dmYC5fcCocONkruP');
        $result = \ConvertApi\ConvertApi::convert('txt', ['File' => $pdfPath]);
        return $result->getFile()->getContents();
    }

}

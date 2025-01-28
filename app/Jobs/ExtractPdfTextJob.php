<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Chart;
use Illuminate\Support\Facades\Log;
use Exception;

class ExtractPdfTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chart;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Chart $chart
     * @return void
     */
    public function __construct(Chart $chart)
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
        $chart = $this->chart;

        Log::info($chart->getPrefix() . ' Chart Extracting...');

        try {
            // Attempt to extract text from the PDF associated with the chart
            $pdfPath = $chart->getFirstMedia('pdf')->getPath();
            $text = $this->extractionTestFromConvertApi($pdfPath);

            // Clean and save the extracted text
            $chart->extracted_text = $this->cleanString($text);
            $chart->is_extract = Chart::EXTRACT_COMPLETED;
            $chart->save();

            // Log successful extraction
            Log::info($chart->getPrefix() . ' Chart extracted successfully');
        } catch (\Exception $e) {
            // Log any errors during extraction
            Log::error('Error extracting chart: ' . $chart->getPrefix() . ' - ' . $e->getMessage());
            // You might want to add some retry logic or other error handling here
        }
    }

    /**
     * Extract text from PDF using Convert API.
     *
     * @param string $pdfPath
     * @return string
     */
    protected function extractionTestFromConvertApi(string $pdfPath): string
    {
        // Your implementation for extracting text from the PDF
        return ''; // Replace this with actual implementation
    }

    /**
     * Clean the extracted text.
     *
     * @param string $text
     * @return string
     */
    protected function cleanString(string $text): string
    {
        // Your implementation for cleaning the extracted text
        return trim($text); // Replace this with actual implementation
    }
}

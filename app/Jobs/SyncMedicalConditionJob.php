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

class SyncMedicalConditionJob implements ShouldQueue
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

            Log::info("sync medical condition cron executing...");
           
            if ($chart_chunk) {
                $content = $chart_chunk->content;
                $medicalConditions = MedicalCondition::select('id', 'title', 'code')->get();

                foreach ($medicalConditions as $medicalCondition) {
                    // Check if the $content matches the $medicalCondition title (case-insensitive)
                    $is_matched = isExactMatchCaseInsensitive($content, $medicalCondition->title);
                    
                    if ($is_matched !== false) {
                        // Retrieve the first ChartDetail entry where the medication matches the medical condition's title
                        $chartDetail = ChartDetail::where('chunk_id', $chart_chunk->id)->where('medication', $medicalCondition->title)->first();
                
                        // If no existing ChartDetail entry matches the condition's title, create a new entry
                        if ($chartDetail == null) {
                            // Check if there is an existing ChartDetail for the current chunk with non-null values in the specified fields
                            $existChunkDetail = ChartDetail::where('chunk_id', $chart_chunk->id)
                                ->where(function($query) {
                                    $query->whereNotNull('doctor_id')
                                        ->orWhereNotNull('from_dos')
                                        ->orWhereNotNull('to_dos')
                                        ->orWhereNotNull('institution')
                                        ->orWhereNotNull('comments')
                                        ->orWhereNotNull('dx');
                                })
                                ->first();

                            ++$entry_count; // Increment the entry count
                
                            // Create a new ChartDetail instance
                            $entry = new ChartDetail;
                
                            // Set properties based on the current chunk and medical condition
                            $entry->sequence = $chart_chunk->sequence;
                            $entry->chunk_id = $chart_chunk->id;
                            $entry->chart_id = $chart_chunk->chart_id;
                            $entry->medication = $medicalCondition->title;
                            $entry->native_dx = $medicalCondition->code;
                
                            // If an existing ChartDetail entry is found, copy relevant fields to the new entry
                            $entry->doctor_id = $existChunkDetail ? $existChunkDetail->doctor_id : null;
                            $entry->from_dos = $existChunkDetail ? $existChunkDetail->from_dos : null;
                            $entry->to_dos = $existChunkDetail ? $existChunkDetail->to_dos : null;
                            $entry->institution = $existChunkDetail ? $existChunkDetail->institution : null;
                            $entry->dx = $existChunkDetail ? $existChunkDetail->dx : null;
                            $entry->location = $existChunkDetail ? $existChunkDetail->location : null;
                            $entry->record_type = $existChunkDetail ? $existChunkDetail->record_type : null;
                            $entry->comments = $existChunkDetail ? $existChunkDetail->comments : null;
                
                            // Save the new ChartDetail entry to the database
                            $entry->save();
                        }
                    }
                }
                
                // Mark the chunk as closed
                $chart_chunk->mc_sync_status = ChartChunk::MC_SYNC_STATUS_COMPLETED;
                $chart_chunk->save();

                Log::info($chart_chunk->getPrefix() . " chunk synced with " . $entry_count . " chart details!");
                // sync cron
                syncCrons();
                Log::info($chart_chunk->getPrefix() . " passed for sync cron executed");
            } 
        } catch (Exception $e) {
            Log::error('SyncMedicalConditionJob failed for chart chunk ID ' . $this->chunk->id . ': ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProceededContent;
use Log;
class DestroyProceededContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destroy:proceeded-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for destroy the proceeded content';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $content = ProceededContent::query()->delete();
        \Log::info("Content Destroyed Successfully");
    }
}

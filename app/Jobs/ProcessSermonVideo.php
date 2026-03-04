<?php

namespace App\Jobs;

use App\Models\Sermon;
use App\Services\FreeAISermonService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSermonVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sermon;
    public $tries = 3;
    public $timeout = 1800; // 30 minutes

    public function __construct(Sermon $sermon)
    {
        $this->sermon = $sermon;
    }

    public function handle(FreeAISermonService $aiService)
    {
        Log::info("🎬 Starting to process sermon video: {$this->sermon->id}");
        
        $aiService->processSermonVideo($this->sermon);
        
        Log::info("✅ Completed processing sermon video: {$this->sermon->id}");
    }

    public function failed(\Throwable $exception)
    {
        Log::error("❌ Failed to process sermon video: {$this->sermon->id}", [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        $this->sermon->update([
            'processing_status' => 'failed',
            'processing_error' => $exception->getMessage()
        ]);
    }
}
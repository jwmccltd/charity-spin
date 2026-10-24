<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Throwable;
use Illuminate\Support\Facades\Image;
use Storage;
use App\Services\CampaignService;

class ProcessCampaignArtwork implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public string $fileName;
    public string $originalFileName;
    public string $trackerKey;
    public string|null $id;

    public string $extension;
    public CampaignService $campaignService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $fileName, string $originalFileName, string $extension, string $trackerKey, ?string $id = null)
    {
        $this->fileName = $fileName;
        $this->originalFileName = $originalFileName;
        $this->extension = $extension;
        // A unique identifier sent from the frontend (e.g., a UUID or slug)
        // to track this specific file's progress.
        $this->trackerKey = $trackerKey;
        $this->campaignService = new CampaignService();
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Initialize progress at 0
        Cache::store('redis')
            ->put($this->trackerKey, ['status' => 'processing', 'progress' => 0], 3600);

        $savedPath = Image::fromStorage($this->fileName . '.' . $this->extension, 'artist_artwork')
            ->scale(width: 1200)      
            ->toJpeg(80)     
            ->storeAs('/', $this->fileName . '_processed.jpg', 'artist_artwork'); 

        Storage::disk('artist_artwork')->delete($this->fileName . '.' . $this->extension);
   
        // Store the result
        $this->campaignService->createUpdate([
            'file_key' => $this->trackerKey,
            'artwork_file_path' => $savedPath,
            'original_artwork_file_name' => $this->originalFileName,
        ], $this->id);

        // Mark as finished
        Cache::store('redis')->put($this->trackerKey, ['status' => 'completed', 'progress' => 100], 3600);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->failJob($exception->getMessage());
    }

    /**
     * Helper to gracefully log failure to cache and queue
     */
    private function failJob(string $message): void
    {
        Cache::store('redis')->put($this->trackerKey, [
            'status' => 'failed',
            'progress' => 0,
            'error' => $message
        ], 3600);

        $this->fail($message);
    }
}

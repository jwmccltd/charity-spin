<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Throwable;
use Storage;
use App\Services\CampaignService;

class ProcessCampaignAudio implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public string $fileName;
    public string $originalFileName;
    public string $trackerKey;
    public string|null $id;
    public int $userId;

    public string $extension;
    public CampaignService $campaignService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $fileName, string $originalFileName, string $extension, string $trackerKey, int $userId, ?string $id = null)
    {
        $this->fileName = $fileName;
        $this->originalFileName = $originalFileName;
        $this->extension = $extension;
        // A unique identifier sent from the frontend (e.g., a UUID or slug)
        // to track this specific file's progress.
        $this->trackerKey = $trackerKey;
        $this->campaignService = new CampaignService();
        $this->id = $id;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Initialize progress at 0
        Cache::store('redis')
            ->put($this->trackerKey, ['status' => 'processing', 'progress' => 0], 3600);

        $file = FFMpeg::fromDisk('artist_mp3s')->open($this->fileName . '.' . $this->extension);
        $audioStream = $file->getAudioStream();

        if (!$audioStream) {
            $this->failJob('The uploaded media does not contain an audio track.');
            return;
        }

        $file->export()
            ->toDisk('artist_mp3s')
            ->addFilter([
                '-filter:a',
                'loudnorm=I=-14:LRA=11:TP=-1',
            ])
            ->addFilter([
                '-q:a',
                '0',
            ])
            ->inFormat(new Mp3)
            ->onProgress(function ($percentage) {
                // Update the Redis cache with the current progress percentage
                Cache::store('redis')->put($this->trackerKey, [
                    'status' => 'processing',
                    'progress' => (int) $percentage
                ], 3600);
            })
            ->save($this->fileName . '_processed.mp3'); // Fixed: changed "$fileName" string literal to variable

        Storage::disk('artist_mp3s')->delete($this->fileName . '.' . $this->extension);

        $this->campaignService->createUpdate([
            'file_key' => $this->trackerKey,
            'audio_file_path' => $this->fileName . '_processed.mp3',
            'original_audio_file_name' => $this->originalFileName,
            'user_id' => $this->userId,
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

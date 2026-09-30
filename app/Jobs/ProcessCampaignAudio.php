<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Audio\Mp3;
use Throwable;

class ProcessCampaignAudio implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public string $fileName;
    public string $trackerKey;

    /**
     * Create a new job instance.
     */
    public function __construct($fileName, string $trackerKey)
    {
        $this->fileName = $fileName;
        // A unique identifier sent from the frontend (e.g., a UUID or slug)
        // to track this specific file's progress.
        $this->trackerKey = $trackerKey;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Initialize progress at 0
        Cache::store('redis')->put($this->trackerKey, ['status' => 'processing', 'progress' => 0], 3600);

        $file = FFMpeg::fromDisk('artist_mp3s')->open($this->fileName);
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
            ->save($this->fileName); // Fixed: changed "$fileName" string literal to variable

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

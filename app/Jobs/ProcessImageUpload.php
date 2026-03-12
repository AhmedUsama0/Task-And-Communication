<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Throwable;

class ProcessImageUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const THUMB_WIDTH = 400;

    private const THUMB_HEIGHT = 400;

    private const JPEG_QUALITY = 80;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(public string $tempPath, public string $targetPath) {}

    public function handle(): void
    {
        $localStorage = Storage::disk('local');

        if (!$localStorage->exists($this->tempPath)) {
            return;
        }

        try {
            $manager = ImageManager::gd();

            $thumbnailData = $manager
                ->read($localStorage->path($this->tempPath))
                ->cover(self::THUMB_WIDTH, self::THUMB_HEIGHT)
                ->toJpeg(self::JPEG_QUALITY)
                ->toString();

            $isUploaded = Storage::disk('s3')->put($this->targetPath, $thumbnailData, [
                'CacheControl' => 'max-age=31536000, public',
            ]);

            if ($isUploaded) {
                $localStorage->delete($this->tempPath);
            }
        } catch (Throwable $e) {
            Log::error('Image processing failed', [
                'error' => $e->getMessage(),
                'temp' => $this->tempPath,
            ]);

            throw $e;
        }
    }

    public function failed(Throwable $e): void
    {
        Log::error('Image job failed permanently', ['error' => $e->getMessage()]);

        Storage::disk('local')->delete($this->tempPath);
    }
}

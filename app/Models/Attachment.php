<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = ['message_id', 'path'];

    public function getPathAttribute(): string
    {
        $path = $this->getRawOriginal('path') ?? null;

        if (! $path) {
            return '';
        }

        try {
            return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(30));
        } catch (Exception $e) {
            Log::error(sprintf('failed to generate URL for an attachment in %s: %s', __CLASS__, $e->getMessage()));
        }

        return '';
    }
}

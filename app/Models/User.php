<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Throwable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;
    use Searchable;

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->getFullName(),
        ];
    }

    public function searchableAs(): string
    {
        return 'users_index';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'image',
    ];

    // /**
    //  * mutator for the password attribute
    //  * @param string $password
    //  * @return void
    //  */
    // public function setPasswordAttribute(string $password): void
    // {
    //     $this->attributes['password'] = bcrypt($password);
    // }

    public function getFullName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Get the user profile picture.
     */
    public function getImageAttribute(): string
    {
        $imagePath = $this->getRawOriginal('image') ?? null;
        $fallbackImage = 'https://ui-avatars.com/api/?name='.urlencode($this->getFullName()).'&color=7F9CF5&background=EBF4FF';

        if (! $imagePath) {
            return $fallbackImage;
        }

        // Small difference between temporary URL time and cached image URL as a buffer to ensure the image will not be broken
        // Because there is a time consumed till the URL is served from the cache and passed to the browser
        // And the browser sends a request with the link, so if during this time the URL expires then we will end
        // serving an expired URL to the user, so this gap acts as a safety by removing the current URL from the cache and generate a new one
        // before the current URL expired, and at this point we will have two URLs, the new one and the old one which will take 20 seconds to expire.
        $cacheKey = $imagePath.'-'.$this->id;

        try {
            $cacheImageUrl = Cache::get($cacheKey);

            if ($cacheImageUrl) {
                return $cacheImageUrl;
            }
        } catch (Throwable $e) {
            Log::warning(sprintf('Failed to get the cache for image in %s: %s', __CLASS__, $e->getMessage()));
        }

        try {
            $userImage = Storage::disk('s3')->temporaryUrl($imagePath, now()->addMinutes(5));

            try {
                Cache::put($cacheKey, $userImage, now()->addMinutes(4)->addSeconds(40));
            } catch (Throwable $e) {
                Log::warning(sprintf('Failed to cache the image in %s: %s', __CLASS__, $e->getMessage()));
            }

            return $userImage;
        } catch (Throwable $e) {
            Log::error(sprintf('Failed to connect to s3 in %s: %s', __CLASS__, $e->getMessage()));
        }

        return $fallbackImage;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // /**
    //  * Get the attributes that should be cast.
    //  *
    //  * @return array<string, string>
    //  */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }

    /**
     * Get all projects you own.
     */
    public function myProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get all projects that you are member of.
     */
    public function projectsAsMember(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_team');
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants');
    }
}

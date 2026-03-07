<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_team');
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class);
    }

    public function statuses(): BelongsToMany
    {
        return $this->belongsToMany(Status::class);
    }
}

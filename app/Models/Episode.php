<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'anime_id',
        'episode_number',
        'title',
        'slug',
        'thumbnail',
        'rating',
        'synopsis',
        'video',
        'duration',
        'is_locked',
        'unlock_cost',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'unlock_cost' => 'integer',
    ];

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }

    public function unlockedEpisodes()
    {
        return $this->hasMany(UnlockedEpisode::class);
    }

    public function watchProgress()
    {
        return $this->hasMany(WatchProgress::class);
    }
}

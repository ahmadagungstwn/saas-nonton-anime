<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchProgress extends Model
{
    protected $fillable = [
        'user_id',
        'episode_id',
        'last_watched_at',
        'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}

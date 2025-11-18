<?php

namespace App\Models;

use App\Models\Episode;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasTags;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'rating',
        'release_year',
        'is_trending',
        'is_top_choice',
    ];

    protected $casts = [
        'is_trending' => 'boolean',
        'is_top_choice' => 'boolean',
    ];

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}

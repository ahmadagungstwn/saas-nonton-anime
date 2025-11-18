<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Interfaces\AnimeRepositoryInterface;

class AnimeRepository implements AnimeRepositoryInterface
{
    public function getAll($filters = [], $limit = 6)
    {
        $query = Anime::query();

        if (isset($filters['is_trending'])) {
            $query->where('is_trending', $filters['is_trending']);
        }

        if (isset($filters['is_top_choice'])) {
            $query->where('is_top_choice', $filters['is_top_choice']);
        }

        return $query->paginate($limit);
    }

    public function getLatestAnime($limit = 6)
    {
        return Anime::withCount('episodes')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function getBySlug($slug)
    {
        return Anime::where('slug', $slug)
            ->withCount('episodes')
            ->first();
    }

    public function getAnimeRecommendations($anime)
    {
        return Anime::where('id', '!=', $anime->id)
            ->whereIn('id', $anime->tags->pluck('id'))
            ->inRandomOrder()
            ->take(4)
            ->get();
    }
}

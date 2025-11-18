<?php

namespace App\Interfaces;

interface AnimeRepositoryInterface
{
    public function getAll($filters = [], $limit = 6);
    public function getLatestAnime($limit = 6);

    public function getBySlug($slug);
    public function getAnimeRecommendations($anime);
}

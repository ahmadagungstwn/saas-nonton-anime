<?php

namespace App\Interfaces;

interface EpisodeRepositoryInterface
{
    public function getById($slug);
    public function getByAnimeId($animeId);
    public function isUnLocket($episodeId);
    public function unlock($episodeId);
}

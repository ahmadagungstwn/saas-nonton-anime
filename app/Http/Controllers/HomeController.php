<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Interfaces\AnimeRepositoryInterface;

class HomeController extends Controller
{
    protected $animeRepository;

    public function __construct(AnimeRepositoryInterface $animeRepository)
    {
        $this->animeRepository = $animeRepository;
    }
    public function index()
    {
        $trending = $this->animeRepository->getAll([
            'is_trending' => true,
        ]);

        $topChoice = $this->animeRepository->getAll([
            'is_top_choice' => true,
        ]);

        $latest = $this->animeRepository->getLatestAnime();

        return view('pages.home', compact('trending', 'topChoice', 'latest'));
    }
}

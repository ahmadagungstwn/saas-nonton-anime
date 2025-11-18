<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Episode;
use App\Models\CoinPackage;
use Illuminate\Http\Request;
use App\Interfaces\AnimeRepositoryInterface;
use App\Interfaces\EpisodeRepositoryInterface;
use App\Interfaces\CoinPackageRepositoryInterface;

class AnimeController extends Controller
{
    protected $animeRepository;
    protected $episodeRepository;
    protected $coinPackageRepository;


    public function __construct(
        AnimeRepositoryInterface $animeRepository,
        EpisodeRepositoryInterface $episodeRepository,
        CoinPackageRepositoryInterface $coinPackageRepository
    ) {
        $this->animeRepository = $animeRepository;
        $this->episodeRepository = $episodeRepository;
        $this->coinPackageRepository = $coinPackageRepository;
    }

    public function show($slug)
    {
        $anime = $this->animeRepository->getBySlug($slug);

        $recommendations = $this->animeRepository->getAnimeRecommendations($anime);

        return view('pages.anime.show', compact(
            'anime',
            'recommendations'
        ));
    }

    public function play($animeSlug, $episodeSlug)
    {
        $anime = $this->animeRepository->getBySlug($animeSlug);
        $episode = $this->episodeRepository->getById($episodeSlug);
        $episodes = $this->episodeRepository->getByAnimeId($anime->id);
        $isUnlocked = $this->episodeRepository->isUnLocket($episode->id);
        $coinPackages = $this->coinPackageRepository->getAll();

        if (!$isUnlocked && $episode->is_locked == true) {
            $episode->video = null;
        }

        return view('pages.anime.play', compact(
            'anime',
            'episode',
            'episodes',
            'isUnlocked',
            'coinPackages'
        ));
    }

    public function unlockEpisode($animeSlug, $episodeId)
    {
        $episode = $this->episodeRepository->unlock($episodeId);

        return redirect()->route('anime.play', [
            $animeSlug,
            $episode->slug
        ]);
    }
}

<?php

namespace App\Repositories;

use stdClass;
use App\Models\User;
use App\Models\Anime;
use App\Models\Episode;
use App\Models\UnlockedEpisode;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\EpisodeRepositoryInterface;

class EpisodeRepository implements EpisodeRepositoryInterface
{
    public function getById($slug)
    {
        return Episode::where('slug', $slug)->first();
    }

    public function getByAnimeId($animeId)
    {
        $userId = Auth::id();

        $unlockedEpisodeIds = Episode::query()
            ->join('unlocked_episodes', 'episodes.id', '=', 'unlocked_episodes.episode_id')
            ->where('episodes.anime_id', $animeId)
            ->where('unlocked_episodes.user_id', $userId)
            ->pluck('episodes.id');

        $episodes = Episode::where('anime_id', $animeId)
            ->select('id', 'episode_number', 'is_locked', 'slug',  'unlock_cost')
            ->orderBy('episode_number', 'asc')
            ->get();

        return $episodes->map(function ($episode) use ($unlockedEpisodeIds) {
            $isUnlockedForUser = !$episode->is_locked || $unlockedEpisodeIds->contains($episode->id);

            $episodeObject = new stdClass();
            $episodeObject->id = $episode->id;
            $episodeObject->episode_number = $episode->episode_number;
            $episodeObject->slug = $episode->slug;
            $episodeObject->is_locked = $episode->is_locked;
            $episodeObject->is_unlocked_for_user = $isUnlockedForUser;
            $episodeObject->unlock_cost = $episode->unlock_cost;

            return $episodeObject;
        });
    }

    public function isUnLocket($episodeId)
    {
        return UnlockedEpisode::where('user_id', Auth::user()->id)->where('episode_id', $episodeId)->first();
    }


    public function unlock($episodeId)
    {
        $user = User::find(Auth::user()->id);

        $episode = Episode::find($episodeId);

        if ($user->wallet->coin_balance < $episode->unlock_cost) {
            return false;
        }

        UnlockedEpisode::create([
            'user_id' => $user->id,
            'episode_id' => $episodeId,
        ]);

        $user->wallet->coin_balance -= $episode->unlock_cost;
        $user->wallet->save();

        return $episode;
    }
}

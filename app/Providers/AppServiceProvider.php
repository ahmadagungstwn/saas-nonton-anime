<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\AnimeRepository;
use App\Repositories\EpisodeRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\CoinTopUpRepository;
use App\Interfaces\AuthRepositoryInterface;
use App\Repositories\CoinPackageRepository;
use App\Interfaces\AnimeRepositoryInterface;
use App\Interfaces\EpisodeRepositoryInterface;
use App\Interfaces\CoinTopUpRepositoryInterface;
use App\Interfaces\CoinPackageRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AnimeRepositoryInterface::class, AnimeRepository::class);
        $this->app->bind(EpisodeRepositoryInterface::class, EpisodeRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(CoinTopUpRepositoryInterface::class, CoinTopUpRepository::class);
        $this->app->bind(CoinPackageRepositoryInterface::class, CoinPackageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

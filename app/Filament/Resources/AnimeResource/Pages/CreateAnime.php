<?php

namespace App\Filament\Resources\AnimeResource\Pages;

use App\Filament\Resources\AnimeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnime extends CreateRecord
{
    protected static string $resource = AnimeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?String
    {
        return 'Anime berhasil ditambahkan';
    }
}

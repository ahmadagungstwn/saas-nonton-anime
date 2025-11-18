<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Episode;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularEpisodesWidget extends BaseWidget
{
    protected static ?string $heading = 'Episod Terpopuler dalam Minggu Ini';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $weekStart = Carbon::now()->startOfWeek();

        return $table
            ->query(
                Episode::query()
                    ->withCount(['unlockedEpisodes as unlocks_this_week' => function (Builder $query) use ($weekStart) {
                        $query->whereBetween('created_at', [$weekStart, Carbon::now()]);
                    }])
                    ->with(['anime'])
                    ->having('unlocks_this_week', '>', 0)
                    ->orderByDesc('unlocks_this_week')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('anime.title')
                    ->label('Judul Anime')
                    ->limit(30)
                    ->tooltip(function (Episode $record): string {
                        return $record->anime->title;
                    }),

                Tables\Columns\TextColumn::make('episode_number')
                    ->label('Episode Number')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Episode')
                    ->limit(40)
                    ->tooltip(function (Episode $record): string {
                        return $record->title;
                    }),

                Tables\Columns\TextColumn::make('unlocks_this_week')
                    ->label('Unlock Minggu Ini')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unlock_cost')
                    ->label('Biaya Unlock')
                    ->money('IDR', true)
                    ->sortable()
                    ->color('warning'),
            ])
            ->defaultSort('unlocks_this_week', 'desc')
            ->paginated(false);
    }
}

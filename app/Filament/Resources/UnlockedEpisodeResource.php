<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnlockedEpisodeResource\Pages;
use App\Filament\Resources\UnlockedEpisodeResource\RelationManagers;
use App\Models\UnlockedEpisode;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnlockedEpisodeResource extends Resource
{
    protected static ?string $model = UnlockedEpisode::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-open';
    protected static ?string $navigationGroup = 'Manajemen User';

    protected static ?string $navigationLabel = 'Riwayat Unlocked Episode';
    protected static ?string $pluralModelLabel = 'Riwayat Unlocked Episode';
    protected static ?string $pluralLabel = 'Riwayat Unlocked Episode';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('episode.anime.title')
                    ->label('Anime')
                    ->searchable(),
                TextColumn::make('episode.title')
                    ->label('Judul Episode')
                    ->searchable(),
                TextColumn::make('episode.episode_number')
                    ->label('Episode')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Unlocked')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('episode_id')
                    ->label('Episode')
                    ->relationship('episode', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnlockedEpisodes::route('/'),
            'create' => Pages\CreateUnlockedEpisode::route('/create'),
            'edit' => Pages\EditUnlockedEpisode::route('/{record}/edit'),
        ];
    }
}

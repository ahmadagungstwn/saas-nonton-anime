<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EpisodeResource\Pages;
use App\Models\Episode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EpisodeResource extends Resource
{
    protected static ?string $model = Episode::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Manajemen Konten';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Episode Details')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('episode-thumbnails')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('episode_number')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Episode #')
                            ->helperText('Must be unique within the season'),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Episode')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., The Beginning, Final Battle'),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->helperText('Gunakan huruf kecil dan tanda strip (-)'),

                        Forms\Components\Textarea::make('synopsis')
                            ->maxLength(65535)
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Video Content')
                    ->schema([
                        Forms\Components\FileUpload::make('video')
                            ->label('Video File')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/x-matroska'])
                            ->maxSize(512000) // 500MB
                            ->directory('episode-videos')
                            ->helperText('Accepted formats: MP4, WebM, MKV (max 500MB)')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('duration')
                            ->label('Duration')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., 01:23:45')
                            ->helperText('Format: HH:MM:SS'),

                        Forms\Components\Placeholder::make('video_info')
                            ->label('Alternative')
                            ->content('Anda juga dapat menyimpan URL video atau tautan streaming video.')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Kontro Akses')
                    ->schema([
                        Forms\Components\Toggle::make('is_locked')
                            ->label('Lock Episode')
                            ->helperText('Users need to unlock this episode')
                            ->live()
                            ->default(false),

                        Forms\Components\TextInput::make('unlock_cost')
                            ->label('Unlock Cost (Coins)')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->suffix('coins')
                            ->hidden(fn(Forms\Get $get) => !$get('is_locked'))
                            ->helperText('Set to 0 for free episodes'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->circular(),

                Tables\Columns\TextColumn::make('anime.title')
                    ->label('Anime')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('episode_number')
                    ->label('EP #')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Episode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap()
                    ->limit(40),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning')
                    ->default('N/A'),

                Tables\Columns\IconColumn::make('video')
                    ->label('Video')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_locked')
                    ->label('Locked')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('warning')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('unlock_cost')
                    ->label('Cost')
                    ->suffix(' 🪙')
                    ->sortable()
                    ->default('Free')
                    ->color(fn($state) => $state > 0 ? 'warning' : 'success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                Tables\Filters\TernaryFilter::make('is_locked')
                    ->label('Locked Episodes'),

                Tables\Filters\TernaryFilter::make('video')
                    ->label('Has Video')
                    ->queries(
                        true: fn($query) => $query->whereNotNull('video'),
                        false: fn($query) => $query->whereNull('video'),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('lock')
                        ->label('Lock Episodes')
                        ->icon('heroicon-o-lock-closed')
                        ->color('warning')
                        ->action(function ($records, array $data) {
                            $records->each->update([
                                'is_locked' => true,
                                'unlock_cost' => $data['cost'] ?? 0,
                            ]);
                        })
                        ->form([
                            Forms\Components\TextInput::make('cost')
                                ->label('Unlock Cost')
                                ->numeric()
                                ->default(100)
                                ->required(),
                        ]),

                    Tables\Actions\BulkAction::make('unlock')
                        ->label('Unlock Episodes')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->action(fn($records) => $records->each->update([
                            'is_locked' => false,
                            'unlock_cost' => 0,
                        ])),
                ]),
            ])
            ->defaultSort('episode_number', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEpisodes::route('/'),
            'create' => Pages\CreateEpisode::route('/create'),
            'edit' => Pages\EditEpisode::route('/{record}/edit'),
        ];
    }
}

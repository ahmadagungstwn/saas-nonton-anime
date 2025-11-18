<?php

namespace App\Filament\Resources\AnimeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EpisodesRelationManager extends RelationManager
{
    protected static string $relationship = 'episodes';

    protected static ?string $title = 'Episodes';

    protected static ?string $icon = 'heroicon-o-video-camera';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('episode_number')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->label('Episode #'),

                Forms\Components\TextInput::make('title')
                    ->label('Judul Episode')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->rules(['alpha_dash'])
                    ->helperText('Gunakan huruf kecil dan tanda strip (-)'),

                // Forms\Components\TextInput::make('duration')
                //     ->label('Duration')
                //     ->required()
                //     ->maxLength(255),

                Forms\Components\RichEditor::make('synopsis')
                    ->label('Sinopsis')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('video')
                    ->label('Video File')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/x-matroska'])
                    ->maxSize(512000)
                    ->directory('episode-videos')
                    ->columnSpanFull()
                    ->required(),

                // Forms\Components\TextInput::make('duration')
                //     ->label('Duration')
                //     ->required()
                //     ->maxLength(255)
                //     ->placeholder('e.g., 01:23:45')
                //     ->helperText('Format: HH:MM:SS'),

                Forms\Components\Toggle::make('is_locked')
                    ->label('Lock Episode')
                    ->live()
                    ->default(false),

                Forms\Components\TextInput::make('unlock_cost')
                    ->label('Unlock Cost')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->suffix('coins')
                    ->hidden(fn(Forms\Get $get) => !$get('is_locked')),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('episode_number')
                    ->label('EP #')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Episode')
                    ->searchable()
                    ->weight('bold')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\IconColumn::make('video')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_locked')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('warning')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('unlock_cost')
                    ->suffix(' 🪙')
                    ->default('Free')
                    ->color(fn($state) => $state > 0 ? 'warning' : 'success'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_locked')
                    ->label('Locked'),

                Tables\Filters\TernaryFilter::make('video')
                    ->label('Has Video')
                    ->queries(
                        true: fn($query) => $query->whereNotNull('video'),
                        false: fn($query) => $query->whereNull('video'),
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Episede')
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Auto-increment episode number if not set
                        if (!isset($data['episode_number'])) {
                            $lastEpisode = $this->getOwnerRecord()
                                ->episodes()
                                ->max('episode_number');

                            $data['episode_number'] = ($lastEpisode ?? 0) + 1;
                        }
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('lock')
                        ->label('Lock')
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
                        ->label('Unlock')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->action(fn($records) => $records->each->update([
                            'is_locked' => false,
                            'unlock_cost' => 0,
                        ])),
                ]),
            ])
            ->defaultSort('episode_number', 'asc')
            ->reorderable('episode_number');
    }
}

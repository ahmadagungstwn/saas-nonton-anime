<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Anime;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Forms\Components\SpatieTagsInput;
use App\Filament\Resources\AnimeResource\Pages;
use App\Filament\Resources\AnimeResource\RelationManagers\EpisodesRelationManager;
use Filament\Forms\Components\Select;

class AnimeResource extends Resource
{
    protected static ?string $model = Anime::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationGroup = 'Manajemen Konten';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi dasar')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Nama Anime')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, Forms\Set $set) =>
                                $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->helperText('Gunakan huruf kecil dan tanda strip (-)'),

                        SpatieTagsInput::make('tags')
                            ->type('genres')
                            ->label('Genres')
                            ->placeholder('Tambah kategori...')
                            ->required(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Details')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('anime-thumbnails')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('rating')
                            ->label('Rating')
                            ->maxLength(255)
                            ->placeholder('e.g., PG-13, R, TV-MA'),

                        Forms\Components\TextInput::make('release_year')
                            ->label('Tahun Rilis')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y') + 2)
                            ->placeholder(date('Y')),
                    ])->columns(2),

                Forms\Components\Section::make('Status Flags')
                    ->schema([
                        Forms\Components\Toggle::make('is_trending')
                            ->label('Trending')
                            ->helperText('Tampilkan anime ini di bagian tren.'),

                        Forms\Components\Toggle::make('is_top_choice')
                            ->label('Top Choice')
                            ->helperText('Tandai sebagai pilihan Utama'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-anime.png')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Anime')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning')
                    ->default('N/A'),

                Tables\Columns\TextColumn::make('release_year')
                    ->label('Tahun Rilis')
                    ->sortable()
                    ->default('-'),

                Tables\Columns\ToggleColumn::make('is_trending')
                    ->label('Trending')
                    ->onColor('success')
                    ->offColor('gray'),

                Tables\Columns\ToggleColumn::make('is_top_choice')
                    ->label('Top Choice')
                    ->onColor('success')
                    ->offColor('gray'),

                Tables\Columns\TextColumn::make('episodes_count')
                    ->label('Jumlah Episodes')
                    ->counts('episodes')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_trending')
                    ->label('Trending'),

                Tables\Filters\TernaryFilter::make('is_top_choice')
                    ->label('Top Choice'),

                Tables\Filters\Filter::make('release_year')
                    ->form([
                        Forms\Components\TextInput::make('from')
                            ->numeric()
                            ->placeholder('From year'),
                        Forms\Components\TextInput::make('to')
                            ->numeric()
                            ->placeholder('To year'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->where('release_year', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->where('release_year', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Anime Berhasil Dihapus')
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            EpisodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimes::route('/'),
            'create' => Pages\CreateAnime::route('/create'),
            'edit' => Pages\EditAnime::route('/{record}/edit'),
        ];
    }
}

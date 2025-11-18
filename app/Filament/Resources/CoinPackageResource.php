<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CoinPackage;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use App\Filament\Resources\CoinPackageResource\Pages;

class CoinPackageResource extends Resource
{
    protected static ?string $model = CoinPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationLabel = 'Harga Koin';
    protected static ?string $navigationGroup = 'Koin';
    protected static ?string $pluralModelLabel = 'Harga Koin';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Nama Paket')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('coin_amount')
                    ->numeric()
                    ->label('Jumlah Koin')
                    ->default(0)
                    ->required(),
                Forms\Components\TextInput::make('bonus_amount')
                    ->numeric()
                    ->label('Bonus Koin')
                    ->default(0)
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix('Rp ')
                    ->label('Harga')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('coin_amount')
                    ->label('Jumlah Koin')
                    ->sortable(),

                Tables\Columns\TextColumn::make('bonus_amount')
                    ->label('Bonus Koin')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Urutan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('User Berhasil Dihapus')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->reorderable('display_order');
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
            'index' => Pages\ListCoinPackages::route('/'),
            'create' => Pages\CreateCoinPackage::route('/create'),
            'edit' => Pages\EditCoinPackage::route('/{record}/edit'),
        ];
    }
}

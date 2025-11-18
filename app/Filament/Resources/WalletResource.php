<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Models\Wallet;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\WalletResource\Pages;


class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationGroup = 'Manajemen User';

    public static function canEdit(Model $record): bool
    {
        return false;
    }

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
                ImageColumn::make('user.profile_picture')
                    ->default(fn(Wallet $record) => "https://ui-avatars.com/api/?name=" . urlencode($record->user->name))
                    ->label('Foto Profile')
                    ->circular()
                    ->size(50),
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('coin_balance')->label('Balance Coin'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListWallets::route('/'),
            'create' => Pages\CreateWallet::route('/create'),
            'edit' => Pages\EditWallet::route('/{record}/edit'),
        ];
    }
}

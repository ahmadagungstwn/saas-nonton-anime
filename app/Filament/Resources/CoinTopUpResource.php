<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\CoinTopUp;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CoinTopUpResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CoinTopUpResource\RelationManagers;
use App\Models\User;
use Filament\Forms\Components\Select;

class CoinTopUpResource extends Resource
{
    protected static ?string $model = CoinTopUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Top Up Koin';
    protected static ?string $navigationGroup = 'Koin';
    protected static ?string $pluralModelLabel = 'Top Up Koin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->default('TOPUP-' . Str::random(10))
                    ->label('Kode Top Up')
                    ->readOnly(),
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('coin_package_id')
                    ->label('Paket Koin')
                    ->relationship('package', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'success' => 'Success',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User'),

                Tables\Columns\TextColumn::make('package.title')
                    ->label('Paket Koin'),

                Tables\Columns\TextColumn::make('coin_amount')
                    ->label('Jumlah Koin'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Total Harga')
                    ->money('IDR', true),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'success' => 'success',
                        'failed' => 'danger',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Top Up')
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('coin_package_id')
                    ->label('Paket Koin')
                    ->relationship('package', 'title')
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
            'index' => Pages\ListCoinTopUps::route('/'),
            'create' => Pages\CreateCoinTopUp::route('/create'),
            'edit' => Pages\EditCoinTopUp::route('/{record}/edit'),
        ];
    }
}

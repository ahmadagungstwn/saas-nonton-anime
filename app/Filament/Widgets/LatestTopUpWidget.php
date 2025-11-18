<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\CoinTopUp;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTopUpWidget extends BaseWidget
{
    protected static ?string $heading = '5 Top Up Terakhir';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CoinTopUp::query()
                    ->with(['user', 'package'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->limit(20)
                    ->tooltip(fn(CoinTopUp $record): string => $record->user->name),

                Tables\Columns\TextColumn::make('package.title')
                    ->label('Paket')
                    ->limit(25)
                    ->tooltip(fn(CoinTopUp $record): string => $record->package->title ?? '-'),

                Tables\Columns\TextColumn::make('package.price')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->color('primary'),

                Tables\Columns\TextColumn::make('coin_amount')
                    ->label('Koin')
                    ->formatStateUsing(fn($state) => number_format($state) . ' koin')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'success' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'success' => 'Berhasil',
                        'pending' => 'Pending',
                        'failed' => 'Gagal',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->since()
                    ->tooltip(fn(CoinTopUp $record): string => $record->created_at->format('d/m/Y H:i:s')),
            ])->paginated(false);
    }
}

<?php

namespace App\Filament\Resources\CoinTopUpResource\Pages;

use Filament\Actions;
use App\Models\CoinPackage;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CoinTopUpResource;

class CreateCoinTopUp extends CreateRecord
{
    protected static string $resource = CoinTopUpResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?String
    {
        return 'Top up koin berhasil';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $coinPackage = CoinPackage::find($data['coin_package_id']);

        $data['coin_amount'] = $coinPackage->coin_amount + $coinPackage->bonus_amount;

        $data['amount'] = $coinPackage->price;

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->status === 'success') {

            $this->record->user->wallet->update([
                'coin_balance' => $this->record->user->wallet->coin_balance + $this->record->coin_amount
            ]);
        }
    }
}

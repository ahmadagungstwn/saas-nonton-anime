<?php

namespace App\Services;

use App\Interfaces\CoinTopUpRepositoryInterface;
use App\Interfaces\CoinPackageRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CoinTopUpService
{
    protected $topupRepo;
    protected $packageRepo;

    public function __construct(
        CoinTopUpRepositoryInterface $topupRepo,
        CoinPackageRepositoryInterface $packageRepo
    ) {
        $this->topupRepo = $topupRepo;
        $this->packageRepo = $packageRepo;
    }

    public function createTopUp($packageId)
    {
        $package = $this->packageRepo->find($packageId);

        $orderId = "TOPUP-" . time();

        return $this->topupRepo->create([
            'code' => $orderId,
            'user_id' => Auth::id(),
            'coin_package_id' => $packageId,
            'coin_amount' => $package->coin_amount + $package->bonus_amount,
            'amount' => $package->price,
            'status' => 'pending',
        ]);
    }

    public function handleCallback($json)
    {
        $topup = $this->topupRepo->findByCode($json->order_id);

        if (!$topup) return null;

        if (in_array($json->transaction_status, ['capture', 'settlement'])) {

            if ($json->fraud_status ?? null === 'challenge') return;

            // Update status
            $this->topupRepo->markSuccess($topup);

            // Tambah coin ke wallet user
            $user = $topup->user;
            $user->wallet->increment('coin_balance', $topup->coin_amount);
        }

        return $topup;
    }
}

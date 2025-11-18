<?php

namespace App\Repositories;

use App\Models\CoinTopUp;
use App\Interfaces\CoinTopUpRepositoryInterface;

class CoinTopUpRepository implements CoinTopUpRepositoryInterface
{
    public function create(array $data)
    {
        return CoinTopUp::create($data);
    }

    public function findByCode(string $code)
    {
        return CoinTopUp::where("code", $code)->first();
    }

    public function markSuccess($topup)
    {
        if ($topup->status !== 'success') {
            $topup->status = 'success';
            $topup->save();
        }

        return $topup;
    }
}

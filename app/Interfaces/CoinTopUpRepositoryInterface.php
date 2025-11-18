<?php

namespace App\Interfaces;

interface CoinTopUpRepositoryInterface
{
    public function create(array $data);
    public function findByCode(string $code);
    public function markSuccess($topup);
}

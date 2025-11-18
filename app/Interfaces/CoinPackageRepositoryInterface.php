<?php

namespace App\Interfaces;

interface CoinPackageRepositoryInterface
{
    public function getAll();
    public function find($id);
}

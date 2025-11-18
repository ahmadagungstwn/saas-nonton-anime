<?php

namespace App\Repositories;


use App\Models\CoinPackage;
use App\Interfaces\CoinPackageRepositoryInterface;

class CoinPackageRepository implements CoinPackageRepositoryInterface
{
    public function getAll()
    {
        return CoinPackage::orderBy('display_order')->get();
    }


    public function find($id)
    {
        return CoinPackage::find($id);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CoinTopUpService;

class CoinTopUpController extends Controller
{
    public function __construct(private CoinTopUpService $service) {}

    public function topup($packageId)
    {
        // Midtrans Config
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Create Topup
        $topup = $this->service->createTopUp($packageId);

        $snapToken = \Midtrans\Snap::getSnapToken([
            "transaction_details" => [
                "order_id" => $topup->code,
                "gross_amount" => $topup->amount
            ]
        ]);

        return response()->json([
            "snap_token" => $snapToken
        ]);
    }

    public function callback(Request $request)
    {
        $json = json_decode($request->getContent());

        $this->service->handleCallback($json);

        return response()->json(["success" => true]);
    }
}

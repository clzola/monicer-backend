<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\WalletResource;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletsController extends Controller
{
    public function getMyWallet()
    {
        return new WalletResource(
            Wallet::with('owner')
                ->where('owner_id', auth('api')->id())
                ->firstOrFail());
    }
}

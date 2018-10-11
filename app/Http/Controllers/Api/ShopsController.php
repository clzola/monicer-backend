<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ShopResource;
use App\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopsController extends Controller
{
    public function shop(Shop $shop)
    {
        return new ShopResource($shop);
    }

    public function getShops()
    {
        return ShopResource::collection(Shop::paginate(20));
    }
}

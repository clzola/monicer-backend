<?php

namespace App\Http\Controllers\Test;

use App\Events\Test\HelloWorldEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BroadcastController extends Controller
{
    public function test()
    {
        event(new HelloWorldEvent('Lazar'));
    }
}

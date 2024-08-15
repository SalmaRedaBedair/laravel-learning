<?php

namespace App\Http\Controllers;

use App\test\UserMailer;

class TestController extends Controller
{
    public function test()
    {
        dd(app(UserMailer::class));
    }
}

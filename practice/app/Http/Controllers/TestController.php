<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\test\Mailer;
use App\test\UserMailer;

class TestController extends Controller
{
    public function test()
    {
        $user = User::first();
        return new UserResource($user);
    }
}

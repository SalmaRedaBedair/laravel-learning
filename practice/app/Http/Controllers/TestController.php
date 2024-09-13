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
        $user->token = $user->createToken('sanctum', ['list-clips', 'add-delete-clips'])->plainTextToken;
        return ['user' => new UserResource($user)];
    }

    public function testAbility()
    {
       return 'test ability';
    }
}

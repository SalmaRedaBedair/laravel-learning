<?php

namespace App\Http\Controllers;

use App\test\Mailer;
use App\test\UserMailer;

class TestController extends Controller
{
    public function test()
    {
//        $object = app(Mailer::class);
//        echo $object->hello('Salma');
//        $object2 = app(Mailer::class);
//        echo $object2->hello('loma');
        $object = app(UserMailer::class);
        dd($object);
    }
}

<?php

namespace App\Http\Controllers;

class SayHello extends Controller
{
    public function __invoke(){
        return 'Hello man <3';
    }
}

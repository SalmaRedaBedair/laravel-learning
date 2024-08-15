<?php

namespace App\test;


class Mailer
{
    public function __construct()
    {
        echo 'constructing mailer';
    }
    public function hello($name)
    {
        return 'Hello '.$name;
    }
}

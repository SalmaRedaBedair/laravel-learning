<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;
class ClassA extends Facade {
    protected static function getFacadeAccessor() {
        return 'class_a';
    }
}

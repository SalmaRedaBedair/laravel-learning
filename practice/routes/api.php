<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', [TestController::class, 'test']);

Route::get('test-ability', [TestController::class, 'testAbility'])
->middleware(['auth:sanctum', 'ability:list-clips']);

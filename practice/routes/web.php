<?php

use App\Models\Greeting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return 'Hello';
});
Route::get('create-greeting',function(){
    $greeting=new Greeting;
    $greeting->body='Hello world';
    $greeting->save();
});
Route::get('first-greeting',function(){
    return Greeting::first()->body;
});

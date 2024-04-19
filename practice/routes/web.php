<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SayHello;
use App\Http\Controllers\testController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Loma;
use App\Http\Middleware\Sandy;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationEmail;
use App\Models\Post;

use function Pest\Laravel\post;
//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider and all of them will
//| be assigned to the "web" middleware group. Make something great!
//|
//*/
//
//// Route::domain('api.myapp.com')->group(function () {
////     Route::get('/loma', function () {
////         return view('loma');
////     });
//// });
//
//Route::get('loma', function () {
//    return view('loma');
//})->middleware(['auth', 'verified']);
//
//
//Route::redirect('redirect-by-route', 'login');
//
//
//Route::get('/', function () {
//    return view('welcome');
//});
//
//// Route::get('/test1', function () {
////     return view('test1');
//// });
//// Route::get('/test2', function () {
////     return view('test2');
//// });
//
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::get('/dashboard', function () {
//    return Redirect()->guest('loma');
//});
//
//// Route::middleware('auth')->namespace('App\Http\Controllers')->name('profile.')->prefix('profile')->group(function () {
////     Route::get('', [ProfileController::class, 'edit'])->name('edit');
////     Route::patch('', [ProfileController::class, 'update'])->name('update');
////     Route::delete('', [ProfileController::class, 'destroy'])->name('destroy');
//// });
//
//
//// Route::middleware(['auth', 'throttle:60,1'])->group(function () {
////     Route::get('/invitations/{invitation}/{answer}', [InvitationController::class, 'confirm'])
////         ->name('invitations')
////         ->middleware('signed');
//
////     Route::get('/send-invitation-email', function () {
////         $recipient = 'redasalma@std.mans.edu.eg';
//
////         $url = URL::temporarySignedRoute('invitations', now()->addHours(4), [
////             'invitation' => 12345,
////             'answer' => 'yes',
////         ]);
//
////         Mail::to($recipient)->send(new InvitationEmail($url));
//
////         return 'Invitation email sent successfully!';
////     });
//// });
//
//// view()->share('name', 'loma');
//
//// Route::get('sayHello', SayHello::class);
//
//// Route::get('posts/{id}', function ($id) {
////     $post = Post::findOrFail($id);
////     return $post;
//// });
//
//// Route::get('posts2/{post}', function (Post $post) {
////     return $post;
//// });
//// Route::get('titles/{title}', function (Post $title) {
////     return $title;
//// });
//// Route::get('redirect-with-helper', function () {
////     return redirect()->to('register');
//// });
//
//// Route::post('something-you-cant-do', function (Illuminate\Http\Request $request) {
////     return 'no';
////     abort(403, 'You cannot do that!');
////     abort_unless($request->has('magicToken'), 403);
////     abort_if($request->user()->isBanned, 403);
//// });
//// Route::get('test', function () {
////     return view('test');
//// });
//// Route::get('child', function () {
////     return view('child');
//// });
//
//// Route::get('backend/sales/{analytics}', function ($analytics) {
////     return view('backend.sales-graphs')
////         ->with('analytics', $analytics);
//// });
//
//
//
//// // Route::fallback(function () {
//// //     return 'That route is not found.';
//// // });
//
//
require __DIR__ . '/auth.php';
//// Generate a normal link
//// Route::get('invitations/{invitation}/{answer}', [InvitationController::class, 'confirm'])
////     ->name('invitations');
//
//// URL::route('invitations', ['invitation' => 12345, 'answer' => 'yes']);
//
//
//// Generate a signed link
//// URL::signedRoute('invitations', ['invitation' => 12345, 'answer' =>
//// 'yes']);
//// // Generate an expiring (temporary) signed link
//// URL::temporarySignedRoute(
//// 'invitations',
//// now()->addHours(4),
//// ['invitation' => 12345, 'answer' => 'yes']
//// );
//
//// Route::get('search/{post:title}',function(Post $post){
////     return $post;
//// });
//
//// Route::get('loma',function(){
////     return view('loma');
//// });
//
//Route::get('form', function () {
//    return view('form');
//});
//Route::post('form', function () {
//    return redirect('form')
//        ->withInput()
//        ->with(['error' => true, 'message' => 'Whoops!']);
//})->name('form');
//
//// return response()->streamDownload(function () {
////     echo DocumentService::file('myFile')->getContent();
////     }, 'myFile.pdf');
//
//Route::post('/posts',function(Request $request){
//    Post::create($request->all());
//});
//
//
//Route::prefix('dashboard')->name('dashboard.')->group(function(){
//    Route::get('loma',function(){
//        return view('loma');
//    })->name('loma');
//
//    Route::get('sandy',function(){
//        return view('sandy');
//    })->name('sandy');
//});
//
//
//Route::group(['prefix'=>'dashboard'],function(){
//    Route::resource('home',HomeController::class);
//});
//
//Route::group(['prefix'=>'api'],function(){
//    Route::resource('home',ApiHomeController::class);
//});
//
//Route::get('home',[ApiHomeController::class,'index']);
//Route::get('home',[HomeController::class,'index']);
//
//Route::get('salma',function(){
//    return view('loma');
//});
//Route::get('salma',[HomeController::class,'index']);
//
//
//Route::middleware(Loma::class)->get('testo',[HomeController::class,'index']);
//
//// Route::get('form', function(){
////     return view('form');
//// })->name('form');
//
//Route::middleware(['auth','verified'])->get('dashboard', function(){
//    return view('dashboard');
//})->name('dashboard');
//
//
//Route::post('sent/{name}', [testController::class,'sent'])->name('sent');
//Route::get('/form',function(){
//    return view('form');
//});
//
//
//Route::middleware('auth')->group(function () {
//
//    Route::get('hello', function () {
//        return 'Hello';
//    });
//
//
//    Route::get('world', function () {
//        return 'World';
//    });
//
//});
//
//
//Route::get('view',function(){
//    return view('view');
//});
//
//Route::get('child',function(){
//    return view('child');
//});
//
Route::get('posts',[PostController::class, 'index']);
//
//Route::get('call_back',[\App\Http\Controllers\MyFatoorahController::class,'callback'])->name('myfatoorah.callback');


Auth::routes();

Route::middleware(['auth','role:admin'])
    ->get('/home', [\App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::post('comment',[\App\Http\Controllers\HomeController::class,'save_comment'])->name('comment.save');

Route::get('user', [UserController::class, 'index'])->name('user.index');



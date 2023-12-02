<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SayHello;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationEmail;
use App\Models\Post;

use function Pest\Laravel\post;

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

Route::domain('api.myapp.com')->group(function () {
    Route::get('/loma', function () {
    return view('loma');
    });
    });


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test1', function () {
    return view('test1');
});
Route::get('/test2', function () {
    return view('test2');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->namespace('App\Http\Controllers')->name('profile.')->prefix('profile')->group(function () {
    Route::get('', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('', [ProfileController::class, 'update'])->name('update');
    Route::delete('', [ProfileController::class, 'destroy'])->name('destroy');
});


Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/invitations/{invitation}/{answer}', [InvitationController::class, 'confirm'])
        ->name('invitations')
        ->middleware('signed');

    Route::get('/send-invitation-email', function () {
        $recipient = 'redasalma@std.mans.edu.eg';

        $url = URL::temporarySignedRoute('invitations', now()->addHours(4), [
            'invitation' => 12345,
            'answer' => 'yes',
        ]);

        Mail::to($recipient)->send(new InvitationEmail($url));

        return 'Invitation email sent successfully!';
    });
});

view()->share('name', 'loma');

Route::get('sayHello', SayHello::class);

Route::get('posts/{id}', function ($id) {
    $post = Post::findOrFail($id);
    return $post;
});

Route::get('posts2/{post}', function (Post $post) {
    return $post;
});
Route::get('titles/{title}', function (Post $title) {
    return $title;
});
Route::get('redirect-with-helper', function () {
    return redirect()->to('register');
});

Route::post('something-you-cant-do', function (Illuminate\Http\Request $request) {
    return 'no';
    abort(403, 'You cannot do that!');
    abort_unless($request->has('magicToken'), 403);
    abort_if($request->user()->isBanned, 403);
});
Route::get('test', function () {
    return view('test');
});
Route::get('child', function () {
    return view('child');
});

Route::get('backend/sales/{analytics}', function ($analytics) {
    return view('backend.sales-graphs')
        ->with('analytics', $analytics);
});



Route::fallback(function () {
    return 'That route is not found.';
});


require __DIR__ . '/auth.php';

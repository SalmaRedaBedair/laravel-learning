# path prefix
## notice the problem here
```php
Route::get('home',[ApiHomeController::class,'index']);
Route::get('home',[HomeController::class,'index']);
```

## to correct it i must use prefix
```php
Route::group(['prefix'=>'dashboard'],function(){
    Route::resource('home',HomeController::class);
});

Route::group(['prefix'=>'api'],function(){
    Route::resource('home',ApiHomeController::class);
});
```

# controller
## bad practice
```php
Route::get('/',[UserController::class,'index']);
Route::get('{id}',[UserController::class,'show']);
```
## best practice
```php
Route::controller(UserController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('{id}', 'show');
});

```

# named route
```php
Route::prefix('dashboard')->name('dashboard.')->group(function(){
    Route::get('loma',function(){
        return view('loma');
    })->name('loma');
    
    Route::get('sandy',function(){
        return view('sandy');
    })->name('sandy');
});
```
- used in blade systems
```php
<a href="{{ route('dashboard.loma') }}">saloma</a>
```
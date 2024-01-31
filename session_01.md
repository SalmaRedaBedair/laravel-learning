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

# namespace
![](./images/session1.jpg)


# route group
```php
Route::group([
    'middleware' => ['auth','auth.type:super-admin,admin'],
    'as' => 'dashboard.',
    'prefix' => 'admin/dashboard',
], function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware('auth','auth.type:super-admin,admin')
->name('dashboard.')
->prefix('admin/dashboard')
->group(function({
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
}));
```
- these two definitions are equal
- these two group() methods are different
- Route::group 
 - that Route facade is for object from router class
 - that group will call function group in router class
- ->group() 
 - call function group in RouterRegister class
 - that router register call group() in router class and send to it array of attributes and callback function that contain routes 
 - that array of attributes was filled by previous methods in chaining like(name(), prefix(), middleware())
- both of them at the end use group() method in router class 
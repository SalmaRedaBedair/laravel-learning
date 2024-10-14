# code base notes
```text
those are notes that i will apply to my code base while making it
```

## important notes while making auth of my code base
- don't add password to fillable of user
- while updating password use `forceFill`
- make end point to logout from current device
- make end point to logout from all devices
- make end point to logout from specific device(rather than current one)
- handle social login

## important notes in general for all project
- handle all exceptions in Exceptions/Handler.php
- make events for email and notifications
- make chat messages hashed
- use controller authorization and middleware authorization
- use commands and stubs to create your own module and remove it
- read from book while making stubs and commands and anything similar to can write more nice code
- php artisan publish:stub
### Custom response macros
- make modification to your own response and modification provided content
- use that to add all required headers to your code
```php
class AppServiceProvider
{
public function boot()
{
    Response::macro('myJson', function ($content) {
        return response(json_encode($content))
        ->withHeaders(['Content-Type' =>
        'application/json']);
    });
}

// during usage
return response()->myJson(['name' => 'Sangeetha']);
```
- never to forget throttle for rate limit of requests
- use spatie permission
- create token with device name while login, to can show login history
```php
Route::post('sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
    $user = User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    return $user->createToken($request->device_name)->plainTextToken;
});
```
- $modelResource call object not defined in trait but defined in controller that trait used in 
- device token => test
- activity log for all different actions

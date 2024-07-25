# Breeze
## npm
- package manager of js, it is like composer for php
- it install all required packages to make js work
- when i run npm install, all packages in package.json will be installed
- when i run project that have front packages and see that style is not cute, then run npm install to install them
- npm run dev: that transfer all frontend files in resources to public

## webpack.mix.js file
- it is used to write code that will transfer folders in resources to public, when i run npm run dev/prod

## guard
- it is used with session
```php
'web' => [
    'driver' => 'session',
    'provider' => 'users',
],
```
## sanctum
- it uses tokens, there is no guard here 
```php
'api' => [
    'driver' => 'sanctum',
    'provider' => 'users',
]
```
## test
```php
Route::get('/test-guards', function () {
    $userFromWebGuard = Auth::user();
    $userFromApiGuard = Auth::guard('sanctum')->user();

    dd($userFromWebGuard, $userFromApiGuard, Auth::guard('api')->user());
});
```
- guard sanctum is the same as api

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

## web & api auth
- web => auth with session, use cookies
- api => auth with sanctum, token

## difference between passport and sanctum
- passport can be used when i want to login to my account using google 
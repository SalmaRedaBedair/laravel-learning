# chapter 08 (User Authorization and Authentication)
```text
every laravel app need authentication system
that take a lot of time
so laravel make it for us
```
## User Model & migrations
- laravel create model and migration of user
- User model extend `Illuminate\Foundation\Auth\User`
- Auth user implements `Illuminate\Contracts\Auth\Authenticatable` and 
`Illuminate\Contracts\Auth\CanResetPassword` and 
`Illuminate\Contracts\Auth\Access\Authorizable`
which require a lot of methods those traits
`use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;`
implements all of them for us
```php
namespace Illuminate\Foundation\Auth;

class User extends Model implements
AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword,
        MustVerifyEmail;
}

namespace App\Models;
class User extends Illuminate\Foundation\Auth\User
{
    use HasApiTokens, HasFactory, Notifiable;
}
```
## Using the auth() Global Helper and the Auth Facade
- use it to check if user log in or not and also to get the data of the currently logged in user
```php
public function dashboard()
{
if (auth()->guest()) {
    return redirect('sign-up');
}
return view('dashboard')
    ->with('user', auth()->user());
}
class Post extends Model
{
// Disable mass assignment on the author_id field
protected $guarded = ['author_id'];
}
```
## routes/auth.php, Auth Controllers, and Auth Actions
## remember me
In web applications, the "Remember Me" functionality typically involves creating a persistent cookie that keeps the user
logged in even after they close the browser. Here's how it generally works, considering your `expire_on_close` setting:

1. **Session Lifetime (`expire_on_close`):**
    - When `expire_on_close` is set to `true`, sessions are typically ended when the user closes the browser or 
   navigates away from the site. This means the session cookie is not persistent.

2. **Remember Me Functionality:**
    - When a user selects "Remember Me," a separate, persistent cookie is usually set in addition to the session cookie.
    - This persistent cookie has a longer expiration time, often weeks or months into the future, 
    depending on the application's settings.
    - This cookie stores a unique identifier or token that allows the server to recognize the user and automatically log them in,
   bypassing the usual session expiration.

3. **Behavior:**
    - If `expire_on_close` is `true`, the session cookie will be deleted when the user closes their browser.
    - However, the persistent "Remember Me" cookie will remain on the user's device until its expiration date or until 
   the user manually logs out.

4. **Implementation:**
    - When implementing "Remember Me," ensure that the persistent cookie's contents are securely managed and validated 
   on the server side to prevent unauthorized access.

In summary, with `expire_on_close` set to `true`, the "Remember Me" functionality will create a persistent cookie that 
keeps the user logged in beyond the current session, allowing them to stay logged in even after closing the browser, 
until they explicitly log out or the persistent cookie expires.

```php
if (auth()->attempt([
    'email' => request()->input('email'),
    'password' => request()->input('password'),
], request()->filled('remember'))) {
    // Handle the successful login
}
```
## password confirmation
- ask user to reconfirm password after visit the page, like billing
```php
Route::middleware('password.confirm')->get('/', function (){
    return view('welcome');
});

// config/auth.php
'password_timeout' => 10800, // that time is in seconds = 3 hours
```
## Manually Authenticating Users
- most common case for authentication is to enter username and password and use attempt() method to see whether they match or not
- but some times i want to manually authenticate specific user
### for all requests
```php
auth()->loginUsingId(5);

auth()->login($user); // pass any object that implements the illuminate\Contracts\Auth\Authenticatable contract
````

### for only current request
```php
auth()->once(['username' => 'mattstauffer']); // pass any data that identifies the user you want to authenticate
// or
auth()->onceUsingId(5);
```

## Manually Logging Out a User
```php
auth()->logout();
```
### Invalidating Sessions on Other Devices
```php
auth()->logoutOtherDevices($password);
```
## Email verification
- to enable email verification for all route use:
```php
Auth::routes(['verify' => true]);
```
- to enable email verification for specific route use:
```php
Route::get('posts/create', function () {
    // Only verified users may enter...
})->middleware('verified');
```
## Guards
- combination of two pieces: driver and provider
- driver: persists and retrieve authentication state (like session, token)
  - web guard: use session driver
  - api guard: use token driver
- provider: get user by certain criteria (like users, User model)
```php
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ]
    ],
```
### default guard
- i can change default guard in config/auth.php

### Using Other Guards Without Changing the Default
- i must define which guard to use
```php
$apiUser = auth()->guard('api')->user();
```
## Closure Request Guards
- define how to get current auth user according to request in AuthServiceProvider
- i can here define to take user by email and password, send them in every request
```php
    public function boot(): void
    {
        Auth::viaRequest('sanctum', function ($request) {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
            return null;
        });
        // or
        Auth::viaRequest('sanctum', function ($request) {
            $token = $request->bearerToken();
            if ($token) {
                return PersonalAccessToken::findToken($token)->tokenable;
            }
        });
        Auth::viaRequest('session', function ($request) {
            return User::first(); // that is meaningless only for test
        });
    }
```
## creating a custom user provider
- there are two drivers for providers(eloquent and database)
- driver define which model or table it will authenticate against
```php
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],
```
### call auth against two different guards
```php
Auth::guard('users')->user();
Auth::guard('trainees')->user();

// define routes
Route::middleware('auth:trainees')->group(function () {
// Trainee-only routes here
});
```
## Authorization and Roles
### Defining Authorization Rule
- in boot of AuthServiceProvider
```php
class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('update-contact', function ($user, $contact) {
            return $user->id == $contact->user_id;
        });
    }
}
```
### using it
- inside controller or request
````php
if (Gate::allows('update-contact', $contact)) {
    // Update contact
}
// or
if (Gate::denies('update-contact', $contact)) {
    abort(403);
}
````
### make action for users rather than that user
```php
// the first parameter is the user sent inside forUser
Gate::define('destroy-advertisement', function ($user, $advertisement) {
    return $user->id == $advertisement->user_id;
});

if (Gate::forUser(auth()->user())->denies('destroy-advertisement', $Advertisement)) {
    abort(403, 'Unauthorized action.');
}
```

## Resource gates
1. i create policy using `php artisan make:policy PolicyName --model=ModelName`
```php
namespace App\Policies;

namespace App\Policies;

use App\Models\Advertisement;
class AdvertisementPolicy
{
    public function view(Advertisement $advertisement)
    {
        return auth()->user()->id === $advertisement->user_id;
    }

    public function update(Advertisement $advertisement)
    {
        return auth()->user()->id === $advertisement->user_id;
    }

    public function delete($user, Advertisement $advertisement)
    {
        return auth()->user()->id === $advertisement->user_id;
    }
}
```
2. register policy in `app/Providers/AuthServiceProvider.php`
```php
    protected $policies = [
        Advertisement::class => AdvertisementPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::resource('advertisement', AdvertisementPolicy::class, ['delete' => 'delete']);
    }
```
3. use policy inside controller
```php
if(Gate::denies('advertisement.delete', $Advertisement)){
   return $this->respondWithErrors(__('Unauthorized action.'), 403);
}
```
## The Authorize Middleware
- you can use authorize middleware which has shortcut `can`
```php
namespace App\Policies;

class AdvertisementPolicy
{
    public function delete(User $user, Advertisement $advertisement)
    {
        return $user->id === $advertisement->user_id;
    }
}

// inside AuthServiceProvider
protected $policies = [
    Advertisement::class => AdvertisementPolicy::class,
];

public function boot(): void
{
    $this->registerPolicies();
}

// inside api.php
Route::middleware('can:delete,Advertisement')->delete('advertisement/{Advertisement}', [AdvertisementController::class, 'destroy']);
```

### Controller Authorization
- app/Http/Controllers/Controller.php imports AuthorizesRequests trait, which provides three methods for authorization:
  - authorize
  - authorizeForUser
  - authorizeResource
- those methods has the same usage as denies, allows and forUser which are used with the `Gate` facade
- now let's see how it works
- in the previous example, i use those three lines to check for authorization
- **authorize**
```php
if (Gate::denies('update-contact', $contact)) {
    abort(403);
}
```
- if we look at `authorize` method inside `AuthorizesRequests` trait
```php
    public function authorize($ability, $arguments = [])
    {
        [$ability, $arguments] = $this->parseAbilityAndArguments($ability, $arguments);

        return app(Gate::class)->authorize($ability, $arguments);
    }
```
- that will check for current abilities and return 403 unauthorized if not allowed
- now i can remove the above three lines with that only one line
```php
$this->authorize('update-contact', $contact);
```
- also **authorizeForUser** does the same thing as `Gate::forUser`, but it will reduce 2 lines
- **authorizeResource**
  - we can use it inside constructor to call resource policy
  ```php
    public function __construct()
    {
        $this->authorizeResource(Contact::class);
    }
  ```
  
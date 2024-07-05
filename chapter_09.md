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

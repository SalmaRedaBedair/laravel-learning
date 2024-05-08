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


# Goal
- here i will write all nice notes i take along reading books, seeing videos that i think i can use along all my projects to rememer me using them in all coming projects to write more nice code 

# scopes
- that are where caluse we write in our quiries

## local scope
- define it in your model and call it in your query
- we made it if we have condition that we will write many times 

### rules
1. function name must start with scope
2. it must be past with parameter $query, we will not write it while calling
3. to call it we write only the name after scope 'first char small'

**local scopes have two types**
1. **static**
- have no parameters passed, only $query

```php
class Contact
{
 public function scopeActiveVips($query) 
 {
 return $query->where('vip', true)->where('trial', false);
 }
}
```
```php
$activeVips = Contact::activeVips()->get();
```

1. **dynamic**
- pass parameter

```php
class Contact
{
 public function scopeActiveVips($query,$vipStatus) 
 {
 return $query->where('vip', $vipStatus)->where('trial', false);
 }
}
```
```php
$activeVips = Contact::activeVips(true)->get();
```

## global scope
- not called 
- it will run automatically over all query models
- we can run it using closure or using entire class, in both i must call it in model using `boot method`

```php
class Contact extends Model
{
    protected static function boot()
 {
 parent::boot();
 static::addGlobalScope('active', function (Builder $builder) {
 $builder->where('active', true);
 });
 }
}
```
- or create scope class and then call it in boot

```php
namespace App\Scopes;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class ActiveScope implements Scope
{
 public function apply(Builder $builder, Model $model)
 {
 return $builder->where('active', true);
 }
}
```
```php
use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Model;
class Contact extends Model
{
 protected static function boot()
 {
 parent::boot();
static::addGlobalScope(new ActiveScope);
 }
}
```
- but what if i don't want to applay that condition in my query?

## Removing global scopes
```php
$allContacts = Contact::withoutGlobalScope('active')->get(); // if using closure
Contact::withoutGlobalScope(ActiveScope::class)->get(); // if using scope class
Contact::withoutGlobalScopes()->get(); // disaple all scopes
Contact::withoutGlobalScopes([ActiveScope::class, VipScope::class])->get(); // disaple some 
```

# apply cache for routes and config file to improve performance of your application

# routes
## route regex
- you can use regex to make route accept only if the route match specific requirements
```php
Route::get('users/{id}', function ($id) {
 //
})->where('id', '[0-9]+');

Route::get('users/{username}', function ($username) {
 //
})->where('username', '[A-Za-z]+');

Route::get('posts/{id}/{slug}', function ($id, $slug) {
 //
})->where(['id' => '[0-9]+', 'slug' => '[A-Za-z]+']);

// note that {id} & $id and {slug} & $slug are not important to be the same, but it is preferred, it works from left to right
```
## constraint helpers
```php
Route::get('users/{id}/friends/{friendname}', function ($id,
$friendname) {
//
})->whereNumber('id')->whereAlpha('friendname');

Route::get('users/{name}', function ($name) {
//
})->whereAlphaNumeric('name');
// whereAlphaNumeric => allow both letters and numbers

Route::get('users/{id}', function ($id) {
//
})->whereUuid('id');
// whereUuid => Universally Unique Identifier

Route::get('users/{id}', function ($id) {
//
})->whereUlid('id');
// whereUlid => Universally Unique Lexicographically Sortable Identifier

Route::get('friends/types/{type}', function ($type) {
//
})->whereIn('type', ['acquaintance', 'bestie', 'frenemy']);

```
# define middleware in controller or in route defination
- sometimes it is better to define middleware in controller rather than route defination
- in comlex apps it is better to define it in cotroller
- you can use except and only methods to define what is the function that will need that middleware
```php
class DashboardController extends Controller
{
 public function __construct()
 {
 $this->middleware('auth');
 $this->middleware('admin-auth')
 ->only('editUsers');
 $this->middleware('team-member')
 ->except('editUsers');
 }
}
```
# Route fallback
- it is defined to return somthing if no route match
- it must be defiened at the end of the routes
```php
Route::fallback(function () {
    return 'That route is not found.';
});
```
# Routes 
- while making it return to ch3 in book and try to write better code

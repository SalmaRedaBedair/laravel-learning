# Chapter 13: Writing APIs
## The Basics of REST-Like JSON APIs
- Representational State Transfer (REST), styling for building APIs
- APIs with few common characteristics

## Controller Organization and JSON Returns
### Creating an API
- to create api controller with the model 
```text
php artisan make:model Dog --api
```

### if you want to create all
- migration
- seeder
- factory
- policy
- resource
- controller
- update & post requests

use the following command:
```text
php artisan make:model Dog --all
```
# Reading and Sending Headers
## Sending Response Headers in Laravel
```php
Route::get('dogs', function () {
    return response(Dog::all())
        ->header('X-Greatness-Index', 12);
});
```
## Reading Request Headers in Laravel
```php
Route::get('dogs', function (Request $request) {
    var_dump($request->header('Accept'));
});
```
# Eloquent Pagination
- i can return elements with pagination using laravel
- to make it use `paginate` method instead of `get` or `all`
- to get elements paginated pass query param `page` to request
- it also returns some useful links like (prev, next, last, first, current)
````text
{
"current_page": 1,
"data": [
{
'name': 'Fido'
},
{
'name': 'Pickles'
},
{
'name': 'Spot'
}
]
"first_page_url": "http://myapp.com/api/dogs?page=1",
"from": 1,
"last_page": 2,
"last_page_url": "http://myapp.com/api/dogs?page=2",
"links": [
{
"url": null,
"label": "&laquo; Previous",
"active": false
},
{
"url": "http://myapp.com/api/dogs?page=1",
"label": "1",
"active": true
},
{
"url": null,
"label": "Next &raquo;",
"active": false
}
],
"next_page_url": "http://myapp.com/api/dogs?page=2",
"path": "http://myapp.com/api/dogs",
"per_page": 20,
"prev_page_url": null,
"to": 2,
"total": 4
}
````
## Sorting and Filtering
### Storing
- i can make only one code to sort in both directions let's see that code:
- i add - before the column name if want to sort descending
```php
// Handles ?sort=name,-weight
Route::get('dogs', function (Request $request) {
// Grab the query parameter and turn it into an array exploded by
,
$sorts = explode(',', $request->input('sort', ''));
// Create a query
$query = Dog::query();
// Add the sorts one by one
foreach ($sorts as $sortColumn) {
$sortDirection = str_starts_with($sortColumn, '-') ? 'desc' :
'asc';
$sortColumn = ltrim($sortColumn, '-');
$query->orderBy($sortColumn, $sortDirection);
}
// Return
return $query->paginate(20);
});
```
- input like that:
```text
http://myapp.com/api/dogs?sort=name,-weight,age,-height
```
### Filtering Your API Results
```php
Route::get('dogs', function (Request $request) {
    $query = Dog::query();
    $query->when(request()->filled('filter'), function ($query) {
        $filters = explode(',', request('filter'));
        foreach ($filters as $filter) {
            [$criteria, $value] = explode(':', $filter);
            $query->where($criteria, $value);
        }
        return $query;
    });
    return $query->paginate(20);
});
```
## Transforming Results
## API Resources
- resource used to structure response as i want
- calculate data 
- embed other resources
### Creating a Resource Class
```text
php artisan make:resource Dog
```
### Resource Collections
- when want to return more than one entity in response  
```php
DogResource::collection(Dog::all());
```
- *but what is i want to return other data with that collection?*
   - in that case i have to create another resource `DogCollection`
  ```php
  namespace App\Http\Resources;
  use Illuminate\Http\Resources\Json\ResourceCollection;
  class DogCollection extends ResourceCollection
  {
        /**
        * Transform the resource collection into an array.
        *
        * @return array<int|string, mixed>
        */
        public function toArray(Request $request): array
        {
            return [
                    'data' => $this->collection,
                    'links' => [
                        'self' => route('dogs.index'), 
                        // i may also add pdf link for financial report here  
                    ],
                 ];

        }
    }
  ```
### Nesting Relationships
- add key with the relation and return it as resource
```php
public function toArray(Request $request): array
{
    return [
        'name' => $this->name,
        'breed' => $this->breed,
        'friends' => Dog::collection($this->friends),
    ];
}
```
- *note that:*
  - the above example may give an error 
  - so i should eager load the relation when calling the resource
  - i can do that by using `with` method
  - ```php
    return new DogResource(Dog::with('friends')->find($dogId));
    ```
    
### i can check if the relation is loaded then return it
```php
public function toArray(Request $request): array
{
    return [
        'name' => $this->name,
        'breed' => $this->breed,
        // Only load this relationship if it's been eager loaded
        'bones' => BoneResource::collection($this
            ->whenLoaded('bones')),
        // Or only load this relationship if the URL asks for it
        'bones' => $this->when(
        $request->get('include') == 'bones',
            BoneResource::collection($this->bones)
        ),
    ];
}
```
### Conditionally Applying Attributes
- return some attributes based on condition
```php
public function toArray(Request $request): array
{
    return [
        'name' => $this->name,
        'breed' => $this->breed,
        'rating' => $this->when(Auth::user()->canSeeRatings(), 12),
    ];
}
```
### More Customizations for API Resources
## API Authentication
- using sanctum and passport
### API Authentication with Sanctum
- using token, generate token when login
- SPA: use cookies

### Installing Sanctum
```text
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

php artisan migrate
```
```php
Route::get('clips', function () {
  return view('clips.index', ['clips' => Clip::all()]);
})->middleware('auth:sanctum');
```

### Issuing Sanctum tokens manually
1. set up your model 
```php
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```
### generate token
```php
Route::post('tokens/create', function () {
    $token = auth()->user()->createToken(request()->token_name);
    return view('tokens.created', ['token' => $token->plainTextToken]);
});

Route::get('tokens', function () {
    return view('tokens.index', ['tokens' => auth()->user()->tokens]);
});
```
### abilities
```php
$token = $user->createToken(
    request()->token_name, ['list-clips', 'add-delete-clips']
);
```
- to use that abilities, add those to kernel 
```php
'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
```
#### Manually checking a user’s access based on token abilities
```php
if (request()->user()->tokenCan('list-clips')) {
// ...
}
```
#### Using middleware to restrict access based on token scopes
```php
// routes/api.php
Route::get('clips', function () {
// Access token has both the "list-clips" and "add-delete-clips" abilities
})->middleware(['auth:sanctum','abilities:list-clips,add-delete-clips']);
// or

Route::get('clips', function () {
// Access token has at least one of the listed abilities
})->middleware(['auth:sanctum','ability:list-clips,add-delete-clips'])
```
1. add abilities to every generated token 
```php
$user->token = $user->createToken('sanctum', ['list-clips', 'add-delete-clips'])->plainTextToken;
```
2. check for every end point if auth have ability to show that endpoint
````php
Route::get('test-ability', [TestController::class, 'testAbility'])
    ->middleware(['auth:sanctum', 'ability:list-clips2'])
````
*spatie roles and permissions handle that in more simple way*


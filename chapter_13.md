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
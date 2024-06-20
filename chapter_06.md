# chapter 06

```text
laravel is mainly known as php framework
but it also a fullstack 
it has series of components that help me generating frontend code
```

## laravel starter kits

- laravel two starter kits are Breeze & Jetstream
- it provide all required frontend and backend for auth

### Breeze

- when i write

```text
php artisan breeze:install
```

- it will give you that list

```text
 Which stack would you like to install?
  blade .......................................................................................................................................... 0
  react .......................................................................................................................................... 1
  vue ............................................................................................................................................ 2  
  api ............................................................................................................................................ 3
```

### jetstream

- it provide all in breeze
- but it also provide:
    - two factor authentication
    - session management
    - API token management
    - team management feature

### inertia

- front end tool
- build single page app in js
- use laravel app and routes to provide routing and data to each view

## pagination

- divide database data into pages
- `paginate()` can take only one parameter which is number of items per page

### Manually Creating Paginators

```text
if you are not working with laravel eloquent or query builder
and you are making complex queries
you might find yourself need to create your own paginator
```

#### what we will use to create that paginator

- `Illuminate\Pagination\Paginator`
- ` Illuminate\Pagination\LengthAwarePaginator`

#### usage

- `Paginator` provide only next and previous links, no links for each page
- `LengthAwarePaginator` provide links for each page
- both require you manually extract the slice that will be viewed on each page

```php
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator; 
 
Route::get('people', function (Request $request) { 
    $people = [...]; // huge list of people 
 
    $perPage = 15; 
    $offsetPages = $request->input('page', 1) - 1; 
 
    // The Paginator will not slice your array for you 
    $people = array_slice( 
        $people, 
        $offsetPages * $perPage, // start at this offset
        $perPage // number of items took
    ); 
 
    return new Paginator( 
        $people, 
        $perPage 
    );
});
```
## Message Bags
- 
























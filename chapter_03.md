# REST (Representational State Transfer)
![](images/rest.jpg)
-  A RESTful API allows clients to interact with a server using standard HTTP methods, such as GET, POST, PUT, PATCH, and DELETE.

# MVC
## model
- individual database table
## view
- templates that outputs data to user
## controller
1. takes HTTP requests from browser
2. gets right data from databse
3. validate user input
4. send response back to user
## Explain how mvc works with user
![](./images/Screenshot%202023-09-29%20211900.jpg)
1. user send http request via browser to controller
2. controller write/take data to/from the model
3. controller send data to view
4. the view will show data to user 

# HTTP verbs
1. get & post
2. put & delete
3. head, options & patch
4. trace & connect
- get: request resources
- head: version of get, ask for headers
- post: create resources
- put & patch: overwrite resources
- delete: delete resources
- options: ask server which verbs are allowed for that url

# closure
- anonymous function
-  A closure is a function that:
    -  you can pass around as an object
    -  assign to a variable
    -  pass as a parameter to other functions and methods
    -  serialize.

# middleware
- like middleman between application and browser or client
- middleware: check if the user is authoticated, validate input and modify response
- series of filters or actions that can be applied to the request and response.
- Each middleware does a specific task, such as checking if the user is logged in or compressing the response data.

# laravel caching & closure function
- In Laravel, route caching is a feature that can significantly improve the performance of your application by precompiling and storing the routes in a cache file. This cache file allows Laravel to quickly determine which route corresponds to a given URL, without having to go through the process of registering and resolving routes for each request.
- However, when you use route closures (anonymous functions) to define your routes, Laravel cannot cache those routes. This means that for each request, Laravel needs to dynamically evaluate the routes and match them against the requested URL, which takes additional time and resources.
- On the other hand, when you define routes using named controller methods or controller invocations, Laravel can cache those routes. By caching the routes, Laravel can skip the route resolution process and directly retrieve the corresponding route from the cache file, resulting in a significant performance boost.

# Route Parameters
## optional parameter
- you can make parameters optional by adding ? after name of paramater
```php
Route::get('users/{id?}', function ($id = 'fallbackId') {
 //
});
```
## REGEX 
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

// note that {id} & $id and {slug} & $slug are not important to be the same, but it is preferred, it works from left to write
```
# ROUTE name
- it is better because it is easier
- if you change the route you will not change url in front end code
```php
// Defining a route with name() in routes/web.php:
Route::get('members/{id}', 'MembersController@show')->name('members.show');

// Linking the route in a view using the route() helper:
<a href="<?php echo route('members.show', ['id' => 14]); ?>">
```

# Blade
- all blade syntax is compiled into normal php code then cached so it is fast

## now important question...
### he said here code will cashed how it is cashed even if i can update blade and see the result directly without clear cache?

Laravel also provides features for development environments that make it convenient to see changes in Blade templates without manually clearing the cache:

**Automatic Compilation**: In development mode, Laravel will automatically recompile Blade templates if changes are detected in the original .blade.php files. This means that when you make changes to a Blade template during development, Laravel will detect those changes and recompile the template without you needing to manually clear the cache.

**Cache Clearing**: In some cases, if you encounter issues with stale cache or if changes are not reflected as expected, you might need to manually clear the cache using artisan commands like `php artisan view:clear` or `php artisan cache:clear`. This command clears the compiled views cache, forcing Laravel to recompile the Blade templates on the next request.

So, while Blade templates are cached for performance, Laravel provides mechanisms to ensure that changes made during development are reflected in the rendered output without needing to manually clear the cache every time.

# not to use php in blade
- if you have to use php in blade that mean you use code must not be used in that file
- so you must go and write that code in the model or the controller

## Echoing data
- data is safe
```php
{{ $variable }}  == <?= htmlentities($variable) ?>
```
- to echo data without htmlentities
```php
 {!! and !!}
```

# notice the diffrence between two codes
```php
// Parsed as Blade; the value of $bladeVariable is echoed to the view
{{ $bladeVariable }}
// output: value of $bladeVariable

// @ is removed and "{{ handlebarsVariable }}" echoed to the view directly
@{{ handlebarsVariable }}
// output: {{ handlebarsVariable }}


```

# verbatim
- `@verbatim` is a helpful directive for ensuring that specific parts of your template are treated as raw text without any Blade processing
```php
@verbatim
    <div class="container">
        Hello, {{ name }}.
    </div>
@endverbatim
```

# forelse
- @forelse is a @foreach that also allows you to program in a fallback if the
object you’re iterating over is empty.
```php
@forelse ($talks as $talk)
    {{ $talk->title }} ({{ $talk->length }} minutes)<br>
@empty
    No talks this day.
@endforelse

```
# yield with default value
```php
@yield('title','Home page')
```

# foreach & forelse in blade system
- they add new feature not exists in php foreach loops: `$loop variable`
- that variable will return std class object, with those properties
    - index: 0-indexed
    - iteration: 1-indexed
    - remaining: number of element remain in loop
    - count: number of elemnt in loop
    - first & last: boolen indicate first or last
    - even & odd: boolen indicate whether that iteration is odd or even 
    - depth: How many “levels” deep this loop is: 1 for a loop, 2 for a loop
within a loop, etc.
    - parent: refrence to the parent of that loop, if that loop is in another loop, if not return null

# diffrence between show and endsection 
- show: define place of content in parent
- endsection: end of content in child
- section will be shown in child even if you don't override it
```php
// parent
@section('footerScripts')
    <p>loma</p>
    <script src="app.js"></script>
@show

// child 
@section('footerScripts')
    @parent
    <script src="dashboard.js"></script>
@endsection
```
- @stop: used to not show content in either parent nor child
- @parent: it is used to incluede content in the parent to the child

# includeIf, includeWhen and includeFirst
```php
{{-- Include a view if it exists --}}
@includeIf('sidebars.admin', ['some' => 'data'])

{{-- Include a view if a passed variable is truth-y --}}
@includeWhen($user->isAdmin(), 'sidebars.admin', ['some' => 'data'])

{{-- Include the first view that exists from a given array of views --}}
@includeFirst(['customs.header', 'header'], ['some' => 'data'])
```

# stack
- we define stack as a placeholder in parent
- in the childs we push elements to that stack
- push/endpush: adds to the bottom
- prepend/endprepend: adds to the top
- styles will appear only in child i add styles to 
- that stack will be full in every child lonely

```php
<!-- resources/views/layouts/app.blade.php -->
<html>
<head><!-- the head --></head>
<body>
 <!-- the rest of the page -->
 <script src="/css/global.css"></script>
 <!-- the placeholder where stack content will be placed -->
 @stack('scripts')
</body>
</html>

<!-- resources/views/jobs.blade.php -->
@extends('layouts.app')
@push('scripts')
 <!-- push something to the bottom of the stack -->
 <script src="/css/jobs.css"></script>
@endpush

<!-- resources/views/jobs/apply.blade.php -->
@extends('jobs')
@prepend('scripts')
 <!-- push something to the top of the stack -->
 <script src="/css/jobs--apply.css"></script>
@endprepend

//These generate the following result:
<html>
<head><!-- the head --></head>
<body>
 <!-- the rest of the page -->
 <script src="/css/global.css"></script>
 <!-- the placeholder where stack content will be placed -->
 <script src="/css/jobs--apply.css"></script>
 <script src="/css/jobs.css"></script>
</body>
</html>
```

# components and slots
- slot work as a placeholder
```php
<!-- resources/views/partials/modal.blade.php -->
<div class="modal">
<div>{{ $slot }}</div>
 <div class="close button etc">...</div>
</div>

<!-- in another template -->
@component('partials.modal')
 <p>The password you have provided is not valid.
 Here are the rules for valid passwords: [...]</p>
 <p><a href="#">...</a></p>
@endcomponent
```
- that content in component('partials.modal') will be put in plase of $slot

- if i use slot with another name rather than $slot will call it like that 
```php
@slot('title')
.....
@endslot
```
- example contains both types 
```php
<!-- resources/views/partials/modal.blade.php -->
<div class="modal">
 <div class="modal-header">{{ $title }}</div>
 <div>{{ $slot }}</div>
 <div class="close button etc">...</div>
</div>

@component('partials.modal')

 // will be put in place of title
 @slot('title')
 Password validation failure
 @endslot

 // will be put in place of slot
 <p>The password you have provided is not valid.
 Here are the rules for valid passwords: [...]</p>
 <p><a href="#">...</a></p>
@endcomponen
```

# composer view & share variables globally
- we use it to pass data to views instead of calling it in every view 

```php
// in services provider
public function boot()
{
 view()->share('recentPosts', Post::recent());
}
```
# View-scoped view composers with closures
- we use it to share variable with views i put as first parameters

```php
view()->composer('partials.sidebar', function ($view) {
 $view->with('recentPosts', Post::recent());
});
```
# Custom Blade Directives
- you can add your own directive like that ex:
```php
public function boot()
{
    Blade::directive('ifGuest', function () {
        return "<?php if (auth()->guest()): ?>";
    });
}
```
# cache and blade directives
- it is not a good practice to use directives in providers if it contains variables because of caching, if you have variables they will be cached and it will not updates it's values 
- ex: if you visit this page, and the user is a guest, Blade will cache the view with the "You are a guest" message. Now, even if the user logs in during another request, the cached view will still show "You are a guest" because the auth()->guest() result is cached. This can lead to incorrect behavior, so it is better to imlpement it in controller
```php
public function showWelcomePage() {
    $user = auth()->user();
    return view('welcome', ['user' => $user]);
}

@if($user)
    Welcome, {{ $user->name }}!
@else
    You are a guest.
@endif
```
# you can pass parameters to blade directives
```php
// in app serviceprovider
Blade::directive('newlinesToBr', function ($expression) {
    return "<?php echo nl2br({$expression}); ?>";
});

// in blade
@newlinesToBr('coco')
```
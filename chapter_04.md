# Blade
- inspired by `.NET’s Razor engine`
- all blade syntax is compiled into normal php code 
- blade is fast
- it allows you to write native php code in your blade files


# Twig
- we can use twig with laravel instead of blade but we should install it's package
- it is used by all laravel frameworks for generating HTML and XML

## Echoing data
- data is safe
```php
{{ $variable }}  == <?= htmlentities($variable) ?>
```

# forelse
- if array is empty it let me handle that condition 
```php
<?php
$talks = [
    (object)['title' => 'Talk 1', 'length' => 30],
    (object)['title' => 'Talk 2', 'length' => 45],
    (object)['title' => 'Talk 3', 'length' => 20],
];
?>

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
# chapter 07
```text
collecting and handling user data
```
## injecting a request object
- $request instance give access to data posted form, get(parameter search) and url segments
- request() helper and Request facade give the same methods of $request instance
### $request->all()
- return all data use input from different sources
- notice that example
```php
<!-- GET route form view at /get-route -->
<form method="post" action="/signup?utm=12345">
    @csrf
    <input type="text" name="first_name">
    <input type="submit">
</form>
// routes/web.php
Route::post('signup', function (Request $request) {
var_dump($request->all());
});
# notice that data contain data in the form and data passed through query parameters
// Outputs:
/**
* [
* '_token' => 'CSRF token here',
* 'first_name' => 'value',
* 'utm' => 12345,
* ]
*/
```
### $request->except()
- exclude selected data to return
- that data can be form any different sources
### $request->only()
- choose selected data to return
- that data can be form any different sources
### $request->has() & $request->missing()
- check whether that data is passed or not
```php
if ($request->has('utm')) {
    // Do some analytics work
}
```
### $request->whenHas()
- define behavior when that data is passed or not
- the first closure function will be called when that data is passed
- the second closure function will be called when that data is not passed
```php
$utm = $request->whenHas('utm', function($utm) {
    return $utm;
}, function() {
    return 'default';
});
```
### $request->filled()
- same as $request->has() but it also check if that data is empty or not
- try to test that route for more explanation
```php
// http://127.0.0.1:8000/chapter6?name=

Route::get('chapter6', function (\Illuminate\Http\Request $request)
{
    dd($request->has('name'), $request->filled('name'));
});

// Outputs:
/**
* true, false
 */
```
### $request->whenFilled()
- similar to $request->whenHas() but it also check if that data is empty or not
### $request->mergeIfMissing()
- merge data if that data is not passed
- that will be helpful in case if i have checkboxes, those checkboxes will not be passed if they are not selected
```php
Route::post('chapter6', function (\Illuminate\Http\Request $request)
{
    $request->mergeIfMissing(['send_newsletter'=>0]);
    dd($request->all());
});
```
### $request->input()
- it return only the value of specified input field
- it take 2 parameters:
  - the first parameter is the name of the input field
  - the second parameter is the default value, value return if the input field is not passed
- try that example for more explanation
```php
Route::post('post-route', function (Request $request) {
    $userName = $request->input('name', 'Matt');
});
// Outputs:
/**
* if the input field is not passed, Matt will be returned
* if the input field is passed, the value passed will be returned
* if the input field is passed, but with no value, it will return null
 */
```
### $request->method() & $request->is_method()
- $request->method() return the request method (GET, POST, PUT, PATCH, DELETE, OPTIONS)
- $request->is_method() return true if the request method is the same as the parameter
### $request->integer(), ->float(), ->string(), and ->enum()
- cast input fields
### $request->dump() & $request->dd()
- dump whole request object
- dump: dump whole request object and continue the code
- dd: dump whole request object and stop the code
### array input
- use dot notation to access the structure of the array passed in the request
```php
<!-- GET route form view at /employees/create -->
<form method="post" action="/employees/">
@csrf
<input type="text" name="employees[0][firstName]">
<input type="text" name="employees[0][lastName]">
<input type="text" name="employees[1][firstName]">
<input type="text" name="employees[1][lastName]">
<input type="submit">
</form>
// POST route at /employees
Route::post('employees', function (Request $request) {
$employeeZeroFirstName = $request->input('employees.0.firstName');
$allLastNames = $request->input('employees.*.lastName');
$employeeOne = $request->input('employees.1');
var_dump($employeeZeroFirstname, $allLastNames, $employeeOne);
});
// If forms filled out as "Jim" "Smith" "Bob" "Jones":
// $employeeZeroFirstName = 'Jim';
// $allLastNames = ['Smith', 'Jones'];
// $employeeOne = ['firstName' => 'Bob', 'lastName' => 'Jones'];
```
### JSON Input (and $request->json())
- 
### request('firstName') & $request->input('firstName')
- request('firstName') is a shortcut to request()->input('firstName'))
## Route data 
```text
there is two ways to get data from url: via Request object and via Route parameters
```
### From Request
- _what is segment?_
  - every group of characters after domain name in url is called segment
  ```text
  http://www.myapp.com/users/15 
  ```
  - that url has two segments: users and 15
  - we have two available methods to get data from url
     - request()->segment(segment_number)
        - ex: request()->segment(1) returns 15
     - $request->segments() => returns array of segments [users, 15]
  - those segments are 1-based index
  - notice that example two
  ```text
  http://127.0.0.1:8000/api/chapter7/1
  ```
  - that have three segments: api, chapter7, 1
  - to define it in routes/api.php
  ```php
  Route::get('users/{id}', function ($id) {
      
  })
  ```
### From Route Parameters
- are segments i inject into function or controller
```php
// routes/web.php
Route::get('users/{id}', function ($id) {
    // If the user visits myapp.com/users/15/, $id will equal 15
});
```
## uploaded files
- i can get any uploaded file using `$request->file()` it take file name as parameter and return an instance of `Illuminate\Http\Foundation\File\UploadedFile`
- note that if i use `$request->input('fileName')` it will return null, I must use `$request->file('fileName')` instead
### validating file upload
- i can user method `isValid()` to check if the file uploaded successfully or not using that 
```php
if ($request->file('profile_picture')->isValid()) {
    //
}
```
- but it will give me error if user didn't upload the file, so i must use `$request->hasFile('profile_picture')` first
```php
if ($request->hasFile('profile_picture') &&
    $request->file('profile_picture')->isValid()) {
    //
}
```
### store file
- i can use `store` method to store uploaded file to disk 
- it take 2 parameters
- the first parameter is the name of the destination directory
- the second parameter is the name of the disk(s3, local, etc)
```php
if ($request->hasFile('profile_picture')) {
    $path = $request->profile_picture->store('profiles', 's3');
    auth()->user()->profile_picture = $path;
    auth()->user()->save();
}
```
### multipart/form-data
- never to forget to add `enctype="multipart/form-data"` to the form or `content-type: multipart/form-data` in the header of postman
- if you upload file and give you null it might be because you forget to add `multipart/form-data` to the form or header

# validations


# show errors
## i my show them as a package above the form
```php
<ul>
@foreach ($errors->all() as $error)
<li>{{$error}}</li>
@endforeach
</ul>
```
## show them for every filed
```php
// example for filed name
@if($errors->has('name')) 
<div class = "text-danger">
$errors->first('name')
</div>
@endif
```
- that is equal to 
```php
@error('name') // is equal to `@if($errors->has('name'))`
<div class = "text-danger">
$message // is equal to `$errors->first('name')`, that message variable only contain the first error message
</div>
@enderror
```

# @class
```php
@class([
    'form-control',
    'is-invalid' => $errors->has($name) // if there is error is-invalid will add to class list
    // that replace @error('name') is-invalid @enderror inside the class
])
```
- that equal to 
```php
<div class="form-control @error('name') is-invalid @enderror"></div>
```

# old function 
- take two arguments
- first field name, second the default value of that field if there is no old value
- old value appear in case of there is error validations
- default value is important to edit and update to the form
```php
old('name')?? $category->name
// is equal to

old('name', $category->name) 
```

# return data from validation
```php
$data = $request->validated()
```
- that will return only checked data
- if there is any data i don't add in in request validation array, so i can't use that because i may miss some data 

# Request
- if i use Request class i have not to add 
```php
$request->validated();
```
- validation will be done automatically
## change message of specific rule over all application
- from lang/validation.php

# custom validation rule (there is three methods to make it)
- we use it to make our own rule for validation like forbidden some keywords
## run command

```
php artisan make:Rule Filter
```
- inside `app/rules/filter`
```php
public function validate(string $attribute, mixed $value, Closure $fail): void
{
    if (in_array(strtolower($value), $this->forbidden)) {
        $fail("This value for $attribute is forbidden");
    }
}

// to call it i call object Filter 
new Filter($forbidden)
```
## make rule like that `filter:php,laravel,html,css`
- make it in the boot of app/providers/appServiceProvider.php
```php
Validator::extend('filter', function ($attribute, $value,$params) {
    // the value in that $params in that example = [php,laravel,html,css]
    return !(in_array(strtolower($value),$params));
}, "That value is prohibited!");
```
## i can make it using closure too
```php
// inside rule array
function ($attribute, $value, $fail){
    if (in_array(strtolower($value), ['laravel','html','php'])) {
        $fail("This value for $attribute is forbidden");
    }
}
```

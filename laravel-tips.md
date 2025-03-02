# Laravel Tips

## Models Relations
### OrderBy on Eloquent relationships
```php
public function productsByName()
{
    return $this->hasMany(Product::class)->orderBy('name');
}
```

### Add where statement to Many-to-Many relation
you can add where statements to your pivot table using the `wherePivot` method.
```php
public function localClients()
{
     return $this->belongsToMany(Clients::class)
          ->wherePivot('is_local', true);
}
```

### Conditional relationships
If you notice that you use same relationship often with additional "where" condition, you can create a separate relationship method.
```php
public function comments()
{
    return $this->hasMany(Comment::class);
}

public function approvedComments()
{
    return $this->comments()->where('approved', 1);
}
```

### Has Many. How many exactly?
```php
$authors = Author::has('books', '>', 5)->get();
```

### saveMany
- save many records at once, in relation one to many
```php
$post = Post::find(1);
$post->comments()->saveMany([
    new Comment(['message' => 'First comment']),
    new Comment(['message' => 'Second comment']),
]);
```
### multilevel eager loading with exact columns
```php
$users = Book::with('author.country:id,name')->get();
```

### Touch parent updated_at easily
- update updated_at of parent if update its child updated
- ex: add new comment to post 
```php
class Comment extends Model
{
    protected $touches = ['post'];
}
```

### Use withCount() to Calculate Child Relationships Records
If you have hasMany() relationship, and you want to calculate “children” entries, don’t write a special query. For example, if you have posts and comments on your User model, write this withCount():

```php
public function index()
{
$users = User::withCount(['posts', 'comments'])->get();
return view('users', compact('users'));
}
```

And then, in your Blade file, you will access those number with {relationship}_count properties:

```php
@foreach ($users as $user)
<tr>
    <td>{{ $user->name }}</td>
    <td class="text-center">{{ $user->posts_count }}</td>
    <td class="text-center">{{ $user->comments_count }}</td>
</tr>
@endforeach
```

### Extra Filter Query on Relationships
- add filters or ordering to relation
```php
$countries = Country::with(['cities' => function($query) {
    $query->orderBy('population', 'desc');
}])->get();
```

### Check if Relationship Method Exists
```php
$user = User::first();
if (method_exists($user, 'roles')) {
    // Do something with $user->roles()->...
}
```
### Load count
```php
$book->loadCount(['reviews' => function ($query) {
    $query->where('rating', 5);
}]);
```

### add condition to getter with filter
```php
public function getPublishedPostsAttribute()
{
    return $this->posts->filter(fn ($post) => $post->published);
}
```

### whereBelongsTo
- check if object is child of other object, instead of using `where('author_id', $author->id)`
```php
// From:
$query->where('author_id', $author->id)

// To:
$query->whereBelongsTo($author)
```
### The is() method of one-to-one relationships for comparing models
- it is better to make check in relation directly
```php
// BEFORE: the foreign key is taken from the Post model
$post->author_id === $user->id;

// BEFORE: An additional request is made to get the User model from the Author relationship
$post->author->is($user); //that need 3 queries 
select * from `users` where `users`.`id` = 1 limit 1
select * from `posts` limit 1
select * from `users` limit 1

// AFTER
$post->author()->is($user); // that need only 2 queries
select * from `posts` limit 1		
select * from `users` limit 1
```
### clone query 
```php
$query = Product::query();


$today = request()->q_date ?? today();
if($today){
    $query->where('created_at', $today);
}

// lets get active and inactive products
$active_products = $query->where('status', 1)->get(); // this line modified the $query object variable
$inactive_products = $query->where('status', 0)->get(); // so here we will not find any inactive products
```
- that will return active products and blank collection for inactive products
- when i try to find active products, that where condition is added to the query
```php
// query become something like that 
select * from `products` where `status` = 1
```
**how to solve that big problem?**
- so we should use clone here 
```php
$active_products = $query->clone()->where('status', 1)->get(); // it will not modify the $query
$inactive_products = $query->clone()->where('status', 0)->get(); // so we will get inactive products from $query
```
### Merging eloquent collections
```php
$videos = Video::all();
$images = Image::all();

// If there are videos with the same id as images they will get replaced
// You'll end up with missing videos
$allMedia = $videos->merge($images);

// call `toBase()` in your eloquent collection to use the base merge method instead
$allMedia = $videos->toBase()->merge($images);
```
### afterCommit 
- that code will be executed only if transaction is committed, 
- if no transaction, it will be executed also because single query treated as single transaction
```php
DB::transaction(function () {
     $user = User::create([...]);

     $user->teams()->create([...]);
});

class User extends Model
{
     protected static function booted()
     {
          static::created(function ($user) {
               // Will send the email only if the
               // transaction is committed
               DB::afterCommit(function () use ($user) {
                    Mail::send(new WelcomeEmail($user));
               });
          });
     }
}
```
### Eloquent scopes inside of other relationships
```php
// app/Models/Lesson.php:
public function scopePublished($query)
{
     return $query->where('is_published', true);
}

// app/Models/Course.php:
public function lessons(): HasMany
{
     return $this->hasMany(Lesson::class);
}

public function publishedLessons(): HasMany
{
     return $this->lessons()->published();
}
```
### Eloquent where date methods
```php
$products = Product::whereDate('created_at', '2018-01-31')->get();
$products = Product::whereMonth('created_at', '12')->get();
$products = Product::whereDay('created_at', '31')->get();
$products = Product::whereYear('created_at', date('Y'))->get();
$products = Product::whereTime('created_at', '=', '14:13:58')->get();
```
### Fill a column automatically while you persist data to the database
- better to be used in case of creating something which have relation with changeable object
- like, if order is paid, we have to product money in pivot table
```php
// create only
class Country extends Model {
    protected static function boot()
    {
        parent::boot();

        Country::creating(function($model) {
            $model->position = Country::max('position') + 1;
        });
    }
}

// update or create
class Article extends Model
{
    ...
    protected static function boot()
    {
        parent:boot();

        static::saving(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }
}
```
### Grouping by First Letter
- you can make using specific condition
```php
$users = User::all()->groupBy(function($item) {
    return $item->name[0];
});
```

### Never Update the Column
- if you have DB column which you want to store only once and never to update it again
```php
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Model
{
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value, $attributes) => $attributes['email'] ?? $value,
        );
    }
}
```
### Find Many
```php
// Will return Eloquent Model
$user = User::find(1);
// Will return Eloquent Collection
$users = User::find([1,2,3]);

return Product::whereIn('id', $this->productIDs)->get();
// You can do this
return Product::find($this->productIDs)
```

### DB::transaction
- use it instead of using DB::beginTransaction(), DB::commit() and DB::rollBack()
```php
DB::transaction(function () {
    DB::table('users')->update(['votes' => 1]);

    DB::table('posts')->delete();
});
```
### Forget Cache on Save
- delete cache if update or create
- like which was using inside laravel settings
```php
class Post extends Model
{
    // Forget cache key on storing or updating
    public static function boot()
    {
        parent::boot();
        static::saved(function () {
           Cache::forget('posts');
        });
    }
}
```
### Order by Pivot Fields
```php
class Tag extends Model
{
    public $table = 'tags';
}

class Post extends Model
{
    public $table = 'posts';

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id')
            ->using(PostTagPivot::class)
            ->withTimestamps()
            ->withPivot('flag');
    }
}

class PostTagPivot extends Pivot
{
    protected $table = 'post_tag';
}

// Somewhere in the Controller
public function getPostTags($id)
{
    return Post::findOrFail($id)->tags()->orderByPivot('flag', 'desc')->get();
}
```











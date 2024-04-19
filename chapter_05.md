# chapter_04
## URL configuration
- we use it to pass all information need to connect to database in database_url instead of passing them individual 

## different database connection
- you can connect with databases using different drivers
- you define each in config/databases
- i can set default database connection in config file, that will be used by default if i don't call specific one.
- to make query performed in specific database
```php
$users = DB::connection('secondary')->select('select * from users');
```
# column definition
- *rememberToken():* Adds a remember_token column (VARCHAR(100)) for user “remember me” tokens
- *softDeletes():* Adds a deleted_at timestamp for use with soft deletes 

# modifying column
- write new code that represent how will the column look like and append change()
```php
Schema::table('users', function (Blueprint $table) {
 $table->string('name', 100)->change();
});
```
- there are some databases need `doctrine/dbal` to can modify columns in db
```
composer require doctrine/dbal
```

# SQLlite limitations
- if you want to change or drop multiple columns in the same migration, just create multiple calls to Schema::table() within the up() method of your migration, like that:
- that is important when using sqlite only, you must aware with that for testing
```php
public function up()
{
 Schema::table('contacts', function (Blueprint $table)
 {
 $table->dropColumn('is_promoted');
 });
 Schema::table('contacts', function (Blueprint $table)
 {
 $table->dropColumn('alternate_email');
 });
}
```
# Squashing migrations
1. Squashing migrations combines all migration files into a single SQL file.
2. Laravel provides commands like `php artisan schema:dump` to perform this operation.
3. It's useful when there are too many migration files to manage easily.
4. However, using schema dumps prevents the use of in-memory SQLite; it only works with MySQL, PostgreSQL, and local file SQLite databases.
5. if there is migration files not added to single sql file, laravel will execute that single file then those migration files.


# Index
- **Creating Unique Index**: When you create a unique column in Laravel using `$table->unique('email');`, Laravel automatically creates a unique index on the `email` column.

  ```php
  $table->unique('email');
  ```

- **Naming Indexes**: You can specify a custom name for the index by passing it as the second parameter.

  ```php
  $table->unique('email', 'optional_custom_index_name');
  ```

- **Creating Non-Unique Indexes**: You can create indexes for columns that may not be unique using `$table->index('amount');`. This creates a basic index on the `amount` column.

  ```php
  $table->index('amount'); // Basic index
  ```

## Drop Foreign Key
- **Dropping by Index Name**: To delete a foreign key constraint, you can use the `dropForeign()` method by passing the index name. Usually, the index name follows a convention: `table_name + column_name + foreign`.

  ```php
  $table->dropForeign('contacts_user_id_foreign');
  ```

- **Dropping by Column Names**: Alternatively, you can drop a foreign key by passing an array of column names.

  ```php
  $table->dropForeign(['user_id']);
  ```

# running migrations
## migrate:reset
- execute down
## migrate:refresh
- execute down then up
## migrate:fresh
- drop tables then execute up, not execute down
## migrate rollback
- roll back last batch only

# seeder & factory
- add fake data to database, or add data for admin or const data
## factory 
- add fake data for testing
- **to use factory in laravel**
  - i should use trait use factory inside model so that trait will provide factory function which will return name of factory if i use naming conventions
  - if don't use naming convention i should override newFactory() method in the model to return using factory name
```php
protected static function newFactory()
{
    return \Database\Factories\Base\ContactFactory::new();
}
```
- so when i use in seed `contact::factory` it will call that static method which come with trait hasFactory
```php
// Create one
$contact = Contact::factory()->create();

// Create many
Contact::factory()->count(20)->create();
```

## difference between create and make during making factory
- create: safe to database immediately
- make: only creates an instance but not save itm until i finish defining my factory
- we use create if we have relations between tables to can find foreign id with no errors

## pro level model
- that is when i use factory to put data in another one
```php
public function definition(): array
{
    return [
        'name' => 'Lupita Smith',
        'email' => 'lupita@gmail.com',
        'company_id' => Company::factory(), // that will return only id 
        'company_size' => function (array $attributes) {
        // Uses the "company_id" property generated above
    return Company::find($attributes['company_id'])->size;
    },
    ];
}
```
## Attaching related items when generating model factory instances
- create number of instances of model which are related to that model, i can also create those instance with my own definition using state. 

```php
Contact::factory()
    ->has(Address::factory()->count(3))
    ->create()
```
- add own definition using state
```php
$contact = Contact::factory()
    ->has(
        Address::factory()
            ->count(3)
            ->state(function (array $attributes, User $user) {
               return ['label' => $user->name . ' address'];
    })
)
->create(); 
```
### for
- used to add my own definition for related item
```php
Address::factory()
    ->count(3)
    ->for(Contact::factory()->state([
        'name' => 'Imani Carette',
    ]))
->create();
```
## state
- add specific definition to to factory
```php
class ContactFactory extends Factory
{
    protected $model = Contact::class;
    public function definition(): array
    {
        return [
            'name' => 'Lupita Smith',
            'email' => 'lupita@gmail.com',
        ];
    }
    public function vip()
    {
        return $this->state(function (array $attributes) {
        return [
            'vip' => true,
            // Uses the "company_id" property from the
            $attributes
            'company_size' => function () use ($attributes) {
                return Company::find($attributes['company_id'])->size;
            },
        ];
        });
    }
}
// call it
$vip = Contact::factory()->vip()->create();
```

# Query builder
- `fluent inteface`=> use `method chaning`
## Raw sql
- raw sql query, methods with the same name of query(select, update, insert, delete, statement)
- example: 
```php
$countUpdated = DB::update(
    'update contacts set status = ? where id = ?',
    ['donor', $id]
);
```
## method chaining
- chain of methods 
- query builder => build query
### constrained methods (select, where, distinct)
- used to return smaller subset of possible data (only data user need)
```php
$newContacts = DB::table('contact')
    ->where('created_at', '>', now()->subDay())
    ->get();
```
- remember method chaining 

```php
class class1{
    public function function1()
    {
        // code
        return $this;
    }

    public function function2()
    {
        // code
        return $this;
    }
}

// to call method cahning
$obj=new class1();
$obj->function1()->function2();
```
## basic usage of db facade
```php
// basic statement
DB::statement('drop table users');
// here i write my own query as i did in sql 

// parameter binding
DB::select('select * from contacts where validated = ?', [true]);

// fluent method chaining
$users = DB::table('users')->get();

// Joins and other complex calls
DB::table('users')
    ->join('contacts', function ($join) {
    $join->on('users.id', '=', 'contacts.user_id')
    ->where('contacts.type', 'donor');
    })
    ->get();
```
## parameter binding
- it protect quiries from potential sql attacks

```php
$usersOfType = DB::select(
    'select * from users where type = ?',
    [$type]
);
```
- we can also name there parameters for clarity

```php
$usersOfType = DB::select(
    'select * from users where type = :type',
    ['type'=>$type]
);
```

# where in query builder
- you can make multible wheres or make only one where with array of condiction like that 

```php
$newVips = DB::table('contacts')
 ->where('vip', true)
 ->where('created_at', '>', now()->subDay());
// Or
$newVips = DB::table('contacts')->where([
 ['vip', true],
 ['created_at', '>', now()->subDay()],
]);
```
## orWhere
```php
$priorityContacts = DB::table('contacts')
 ->where('vip', true)
 ->orWhere('created_at', '>', now()->subDay())
 ->get();
```

## !!!!!!!!!!!!! be careful while using orWhile with while
```php
// if this or this and this
$canEdit = DB::table('users')
 ->where('admin', true)
 ->orWhere('plan', 'premium')
 ->where('is_plan_owner', true)
 ->get();
SELECT * FROM users
 WHERE admin = 1
 OR plan = 'premium'
 AND is_plan_owner = 1;
```
- **use closure function**
```php
// if this or (this and this) use closure
$canEdit = DB::table('users')
 ->where('admin', true)
 ->orWhere(function ($query) {
 $query->where('plan', 'premium')
 ->where('is_plan_owner', true);
 })
 ->get();
SELECT * FROM users
 WHERE admin = 1
 OR (plan = 'premium' AND is_plan_owner = 1);
```
## whereBetween
```php
$mediumDrinks = DB::table('drinks')
    ->whereBetween('size', [6, 12])
    ->get();
```
## whereIn
```php
$closeBy = DB::table('contacts')
    ->whereIn('state', ['FL', 'GA', 'AL'])
    ->get();
```
## where null and where not null
- check if column is null or not

## skip and take
```php
// returns rows 31-40
$page4 = DB::table('contacts')->skip(30)->take(10)->get();
```

## conditional methods
- make me apply something in closure function depend on the boolean value i passed to it.

### when()
```php
$posts = DB::table('posts')
    ->when($status, function ($query) use ($status) { // $status => boolean (true, false)
        return $query->where('status', $status);
    })
    ->get();
```
### unless
- inverse of when

## joins
- we can make complex joins by passing closure to join

```php
DB::table('users')
 ->join('contacts', function ($join) {
 $join
 ->on('users.id', '=', 'contacts.user_id')
 ->orOn('users.id', '=', 'contacts.proxy_user_id');
 })
 ->get();
```

## union 
- we can union two quiries by write the first and union it with the second using union

```php
$first = DB::table('contacts')
 ->whereNull('first_name');
$contacts = DB::table('contacts')
 ->whereNull('last_name')
 ->union($first)
 ->get();
```
## insertGetId
- will insert and return back id 

```php
$id = DB::table('contacts')->insertGetId([
 'name' => 'Abe Thomas',
 'email' => 'athomas1987@gmail.com',
]);
```

## update
- you can increment or decrement columns using increment or decrement function

```php
DB::table('contacts')->increment('tokens', 5); // +5 all
DB::table('contacts')->decrement('tokens'); // -1 all
```

## truncate
- delete all table and also reset primary id 

```php
DB::table('contacts')->truncate();
```

# json operations
- you may have json columns

```php
// Select all records where the "isAdmin" property of the "options"
// JSON column is set to true
DB::table('users')->where('options->isAdmin', true)->get();

// Update all records, setting the "verified" property
// of the "options" JSON column to true
DB::table('users')->update(['options->isVerified', true]);
```

# best behavior for table name
- snake case "secondary_contacts"
- must be el game3 of el class name

# primary key
- if you want to make another peimary key rather than id which is set by default in laravel you should till it in the model

```php
// in model

// change primary key 
protected $primaryKey = 'contact_id';

// to make it not incrementing automatically
public $incrementing = false;
```

# query builder
- we use model instead of db facade

#  chuck
```php
Contact::chunk(100, function ($contacts) {
 foreach ($contacts as $contact) {
 // Do something with $contact
 }
});
```
- split query into chunks of 100 record each 
- it reduce memory usage, but increase time "remeber that i took time limit when i try to split data into 10 by 10 for around 50000 record" 
- faster excution: Smaller portions of data are easier to handle, and you can avoid bottlenecks and long loading times that might occur when working with large datasets.

# using make during inset
```php
$model = App\Models\Contact::make([
    'phone'=>'kdnskn'
]);
echo $model;
// output: {"phone":"kdnskn"}
// that return data with out id or created at or updated at

$model->save();
echo $model;
// output: {"phone":"kdnskn","updated_at":"2023-11-06T10:21:31.000000Z","created_at":"2023-11-06T10:21:31.000000Z","id":47138}
// after save will return with created at, upadated at and id
```

# frstOrCreate() and frstOrNew()
- it will return first elemnt in db matching those parameters
- if that element is not here it will create it or return it
```php
$contact = Contact::firstOrCreate(['email' => 'luis.ramos@myacme.com']);
```
## diffrence between frstOrCreate() and frstOrNew()
- create: save to database and return
- new: return only 

# soft_delete()
- if you use it and delete any row it will set delete at column to today's date instead of deleting row form db
1. add soft delete to migration 

```php
$table->softDeletes();
```
2. in model

```php
use SoftDeletes; // use the trait
protected $dates = ['deleted_at']; // mark this column as a date
```
## return all items including soft deleted
```php
$allHistoricContacts = Contact::withTrashed()->get();
```

## check if deleted
```php
if ($contact->trashed()) {
 // do something
}
```

## get only deleted
```php
$deletedContacts = Contact::onlyTrashed()->get();
```
## restore
```php
$contact->restore()
```
## force delete
```php
$contact->forceDelete();
```
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
```

# retrieving data with eloquent
## firstWhere
- combine of first and where
```php
Contact::where('name', 'Wilbur Powery')->first();

// is equal to

Contact::firstWhere('name', 'Wilbur Powery');
```

## get & all
- i can ignore the fact that all exists because every time i can use all i can use all instead

## chunk()
- break request into pieces and perform them in batches
- improve memory usage

# translation
- collection of queries that will be all performed or none 
- if there is exception at any queries all queries will be rolled back
- transaction is a part of patch 
- patch may contain many transactions
- make transaction in laravel:
```php
DB::transaction(function () use ($userId, $numVotes) {
// Possibly failing DB query
DB::table('users')
    ->where('id', $userId)
    ->update(['votes' => $numVotes]);
    // Caching query that we don't want to run if the above query fails
DB::table('votes')
    ->where('user_id', $userId)
    ->delete();
});
```
- recommended to use it in case i have two queries related to each other

# fillable
- i still can update values in $fillable if i make instance of that model
- the only can nonfillable not work at is when using create
```php
protected $fillable = ['title', 'content'];

// not work
Post::create([
            'user_id'=>1,
            'title'=>'title',
            'content'=>'content'
        ]);

// work
$post=new Post();
        $post->user_id=1;
        $post->title='title';
        $post->content='content';
        $post->save();
```
# firstOrCreate & firstOrNew
- create save record in db then return it 
- new return new instance without saving

# soft delete
- use only soft delete when you need it 
- never to use it by default
- always clean up data that where deleted using soft delete, if you don't do so database will be larger and larger

## using eloquent soft delete
- every record i delete will set deleted_at to current time
- every query i will retrieve will exclude soft deleted records


# Scopes
## local scope
- add specific condition to specific query
- defined in model class using scope + function name, like that
```php
public function scopeActiveVips($query)
{
return $query->where('vip', true)->where('trial', false);
}
// to call it
$activeVips = Contact::activeVips()->get();
```
## global scope
- add specific condition to all queries
- defined in model class using scope + function name, like that
```php
protected static function boot()
{
parent::boot();
static::addGlobalScope('active', function (Builder $builder) {
$builder->where('active', true);
});
}
// to call it
$allContacts = Contact::active()->get();
```

# accessors
```php
    protected function title():Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value),
        );
    }
```
# mutators
```php
    protected function title():Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value),
        );
    }
```
- note that using mutators not make any changes in database, only in memory
- to save in db use `$model->save()`
```php
        $post=Post::all()->last();
//        $post->title="loma";
//        $post->save();
        dd($post->title, $post);
```
# casting
- allow you to convert data type in your model while read or write
- it is called attribute casting
```php

protected $casts = [
    'email_verified_at' => 'datetime',  
]
```
## custom casting
- if builtin casting doesn't enough ,you can create your own casting
- it is defined using set and get methods
```php
class Encrypted implements CastsAttributes
{

    public function get(Model $model, string $key, mixed $value,
        array $attributes)
    {
        return Crypt::decrypt($value);
    }

    public function set(Model $model, string $key, mixed $value,
        array $attributes)
    {
        return Crypt::encrypt($value);
    }
}
// You can use custom casts in the $casts property on your Eloquent model:
protected $casts = [
'ssn' => \App\Casts\Encrypted::class,
];
```

# collection
- way to work with data in laravel more easy than array
- they are immutable, not changeable, every object in method channing is different from previous object
- they are more easy to work with than array, in array you will have to make functions to make changes in data, that change will be done in array, not like collection objects not change, it creates new one
- anything i can make with collection can be done using array but collection is more easy to work with because of method chaining

## lazy collections
- made to reduce memory usage in process
- using `lazy` or `curser` keywords
- by using lazy collection you app will load only one record of db at a time
```php
$verifiedContacts = App\Contact::cursor()->filter(function
($contact) {
    return $contact->isVerified();
});
```

## what eloquent collection add
- use it when you want to add specific methods to collection, to facilitate work with collection
- you can override collection in laravel and make your own collection class which extends collection in laravel and add all required methods in it
```php
class OrderCollection extends Collection // new collection extends collection 
{
    public function sumBillableAmount() // method to sum billable amount
    {
        return $this->reduce(function ($carry, $order) {
            return $carry + ($order->billable ? $order->amount : 0);
            }, 0);
        }
    }
}
```
- in your model you should override collection method
```php
class Order extends Model
{
    public function newCollection(array $models = [])
    {
        return new OrderCollection($models);
    }
}
```
- when return collection, it will be of type OrderCollection
```php
$orders = Order::all();
$billableAmount = $orders->sumBillableAmount();
```

# eloquent serialization
- convert collection to string (json or array)
```php
$contactArray = Contact::first()->toArray();
$contactJson = Contact::first()->toJson();
```
## return model from route methods
- laravel automatically convert collection to json when returning it in route 
```php
Route::get('api/contacts', function () {
    return Contact::all();
});
```
## hidden & visible fields
- to hide and show fields in collection
- use `hidden` and `visible` methods
```php
class Contact extends Model
{
    public $hidden = ['password', 'remember_token'];
    // or 
    public $visible = ['name', 'email'];
}
```
## loading contents of relation
- by default laravel serialization hide the contents of relation in collection
- to show it use `with`
```php
$user = User::with('contacts')->first();
```
## show hidden fields
- use `makeVisible` method
```php
$array = $user->makeVisible('remember_token')->toArray();
```
## adding accessor to array and json output
- define array `appends` in model
```php
class Contact extends Model
{
    protected $appends = ['full_name'];
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
```
# eloquent relationships
## one to one
```php
class PhoneNumber extends Model
{
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
```
- to insert using relation
```php
$contact->phoneNumbers()->create([
    'number' => '+13138675309',
]);
```
## one to many
- use `associated` & `dissociated` methods
```php
$contact->user()->associate(User::first());
$contact->save();

// and later

$contact->user()->dissociate();
$contact->save();
```
## to return only posts which has comments
```php
$postsWithComments = Post::has('comments')->get();

$postsWithManyComments = Post::has('comments', '>=', 5)->get(); // return only which has 5 comments or more

User::has('posts.comments')->get(); // return only users which has comments in posts

    
```

## where has
```php
    public function scopeOfUser_name($query, $keyword)
    {
        // take two parameters relation name and closure function to search inside relation
        return $query->whereHas('user', function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%');
        });
    }
    // search by name, user_id is stored in the other table
    
    // shorten version
    public function scopeOfUser_name($query, $keyword)
    {
        // take two parameters relation name and closure function to search inside relation
        return $query->whereRelation('user','name', 'like', '%' . $keyword . '%')->get();
    }
```
## has one of many
```php
    public function posts():HasOne
    {
        return $this->hasOne(Post::class)->oldestOfMany();
    }
```
- return only one post which i oldest, even if it has relation 1:m i use `hasOne`

## has many through
- that assume that there is user_id in contacts table and contact_id in phone_numbers table
- so there is a relation between user and phone_numbers through contacts
```php
class User extends Model
{
public function phoneNumbers()
{
    // Newer string-based syntax
    return $this->through('contact')->has('phoneNumber');
    // Traditional syntax
    return $this->hasManyThrough(PhoneNumber::class,
        Contact::class);
}
```
## has one through
- the same as has many through
```php
class User extends Model
{
public function phoneNumbers()
{
    // Newer string-based syntax
    return $this->through('contact')->has('phoneNumber');
    // Traditional syntax
    return $this->hasOneThrough(PhoneNumber::class,
        Contact::class);
}
```
# many to many relationship
## pivot
- if there is attributes in pivot table rather than foreign keys
- and i need to return then with the relation
- i must use `withPivot` method
```php
public function contacts()
{
    return $this->belongsToMany(Contact::class)
        ->withTimestamps()
        ->withPivot('status', 'preferred_greeting');
}
```
### customize pivot key to have different name
```php
// User model
public function groups()
{
    return $this->belongsToMany(Group::class)
        ->withTimestamps()
        ->as('membership');
}
// Using this relationship:
User::first()->groups->each(function ($group) {
    echo sprintf(
        'User joined this group at: %s',
        $group->membership->created_at // access pivot using the new name
    );
});
```
## create relation
```php
$user = User::first();
$contact = Contact::first();
$user->contacts()->save($contact, ['status' => 'donor']);
```

## sync & attach & detach & toggle
- sync add if not exists, if exists ignore
- attach add always
- detach remove
- toggle make the opposite, if exist remove, if not add

# Polymorphic
- used to make only one table to store data for many tables
- make model with function `morphTo` and `morphMany`
```php
// migration
public function up(): void
{
    Schema::create('stars', function (Blueprint $table) {
        $table->id();
        $table->morphs('starrable');
        $table->timestamps();
    });
}
```
```php
// model stars, morphTo
    public function starrable() // that method name must be the same as the column name
    {
        return $this->morphTo();
        // The relationship is defined by two columns in the database: `starrable_type` and `starrable_id`.
        //` starrable_type` stores the class name of the related model, while `starrable_id` stores the primary key value of the related model.
    }
```
```php
// to save that relation
$contact = Contact::first();
$contact->stars()->create();
```
## Many-to-many polymorphic
```php
class Contact extends Model
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}

class Tag extends Model
{
    public function contacts()
    {
        return $this->morphedByMany(Contact::class, 'taggable');
    }
}
```
### add tags
- use `attach` method
```php
$tag = Tag::firstOrCreate(['name' => 'likes-cheese']);
$contact = Contact::first();
$contact->tags()->attach($tag->id);
```

# Child records updating parent record timestamps
- if you have relation belongs to parent model
- and you want to update parent model
- and you want to make child here that update
- define `touches` property in parent model
```php
class PhoneNumber extends Model
{
    protected $touches = ['contact'];
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
```
# eager loading
- use `with` method
- that will load all relations with the current model
- it solve the n+1 problem (lazy loading)
- n+1 problem is when i have many relations with my model it will make n queries to get all relations if i use loop
- eager loading solve that problem with making only two quires, one for item and one for relation
```sql
--  those are the queries using eager loading, only 2 instead of n+1

SELECT * FROM posts;
SELECT * FROM comments WHERE post_id IN (1, 2, 3, ...);
```
## add conditions while eager loading
- pass closure function with the condition
```php
$contacts = Contact::with(['addresses' => function ($query) {
    $query->where('mailable', true);
}])->get();
```
## lazy eager loading
- same as eager loading, it takes only two quires
- it load relation after get the data
```php
$contacts = Contact::all();
if ($showPhoneNumbers) {
    $contacts->load('phoneNumbers');
}
```
## load missing
- use `loadMissing` method to load relation only if it was not loaded
```php
$contacts = Contact::all();
if ($showPhoneNumbers) {
    $contacts->loadMissing('phoneNumbers');
}
```
## prevent lazy loading
- you can disable lazy loading for your entire app at once
- that is because it is an undesirable pattern
```php
// in boot of app service provider
public function boot()
{
    Model::preventLazyLoading(! $this->app->isProduction());
}
```
# eloquent events
- make specific events while making other events
- like when create, send notification
```php
public function boot(): void
{
    $thirdPartyService = new SomeThirdPartyService;
    Contact::creating(function ($contact) use
        ($thirdPartyService) {
    try {
        $thirdPartyService->addContact($contact);
    } catch (Exception $e) {
        Log::error('Failed adding contact to
        ThirdPartyService; canceled.');
        return false; // Cancels Eloquent create()
        }
    });
}
```

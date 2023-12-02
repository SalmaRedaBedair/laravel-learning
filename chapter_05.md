# chapter_04
# diffrent database connection
- you can connect with databsase using diffrent drivers
- you define each in confg/databases
- to make query performed in specifc database
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
- *to rename column*

```php
Schema::table('contacts', function (Blueprint $table)
{
 $table->renameColumn('promoted', 'is_promoted');
});
```
- *to drop column*

```php
Schema::table('contacts', function (Blueprint $table)
{
 $table->dropColumn('votes');
});
```

# SQLlite limitations
- if you want to change or drop multible columns in the same migration, just create multiple calls to Schema::table() within the up() method of your migration, like that:

```sql
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

# diffrence between migrate:fresh & migrate:refresh
- fresh: delte tables and run up not run down
- refresh: run down and then up
- i think it is better to use refresh so if i write any additional code while delteing in down it will be excuted

# diffrence between create and make during making factory
- create: safe to database immediatly
- make: only creates an enstance but not save itm unti i finish defining my factory
- we use create if we have relations between tables to can find foreign id with no errors

# Query builder
- `fluent inteface`=> use `method chaning`

```php
$users = DB::table('users')->where('type', 'donor')->get();
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
## basic usage od db facade
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

## !!!!!!!!!!!!! be careful while using orwhile with while
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
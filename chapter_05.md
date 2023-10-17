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
- 
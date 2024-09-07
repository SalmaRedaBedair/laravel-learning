# chapter 8
## an introduction to artisan
- you use `php artisan` frequently like that:
```text
php artisan make:model Book
```
- if you look at your laravel project root file, you will see that artisan is just a php file,
that is why your are stating your command with `php artisan`
- any thing after `php artisan` is passed to artisan as an arguments
- artisan is a top layer in `symfony console component`
- php artisan commands are changed according to your project or the package,
so you have to check the list of available commands in every new application
```text
php artisan list
```

## Basic artisan commands
### clear-compiled
- remove compiled class file, which is like an internal laravel cache
- run this as first resort when things are going wrong and you don't know why
### down, up
- maintenance mode
### dump-server
- collect and output damped variables
### env
- get environment variables (local, production, etc)
### help
- show help for specific command
### optimize
- clear and refreshes the configuration and route files
### server
- pin up php server at `127.0.0.1:8000`
- you can customize host and port using `--host` and `--port` flags
### --q & --no-interaction
- are used when i run commands without human

## The grouped commands
### auth 
- `auth:clear-resets` flushes all of expired password reset tokens from database
### event
- `event:list` list all events and their listeners in the app
- `event:cache` cache that list of events
- `event:clear` clear the cached list of events
- `event:generate` build missing events and their listeners according to configuration in `EventServiceProvider`
### key
- `key:generate` generate a new encryption key
- if you run that command every currently logged in user will be logged out
- additionally any data you have manually encrypted will be never being decrypted
### stub
- predefined template file that act as a start point for creating new files
- we make commands and all definitions in console file

### notifications
```text
notifications:table
```
- generates a migration that creates the table for database notifications.
### view
```text
  `view:cache`  Compile all of the application's Blade templates
  `view:clear`  Clear all compiled view files
```

## Writing Custom Artisan Commands
```text
artisan make:command YourCommandName
```
- generates a new Artisan command in app/Console/Commands/{YourCommandName}.php
- first argument => class name of command
- second argument => optional => define what terminal command will be
```text
php artisan make:command WelcomeNewUsers --command=email:newusers

- now to run that command

php artisan email:newusers
```
### handle
- inside handle method put code that will be executed when you run that command
```php
class WelcomeNewUsers extends Command
{
/**
* The name and signature of the console command
*
* @var string
*/
protected $signature = 'email:newusers';
/**
* The console command description
*
* @var string
*/
protected $description = 'Command description';
/**
* Execute the console command.
*/
public function handle(): void
{
//
}
}
```
### Arguments and options
- `$signature` take command and any arguments and options for the command
```php
protected $signature = 'password:reset {userId} {--sendEmail}';
```
### required arguments
```php
password:reset {userId}
```
#### optional arguments
```php
password:reset {userId?} 
```
#### optional with default
```php
password:reset {userId=1}
```

### Options—required values, value defaults, and shortcuts
- options are like arguments but it is prefixed with `--`
#### option with no value
```php
password:reset {--sendEmail}
```
#### option with required value
```php
password:reset {--sendEmail=}
```
#### option with default value
```php
password:reset {--sendEmail=default}
```
### array of arguments and options
- use `*`
```php
password:reset {userId=*} {--sendEmail*}

// Argument
php artisan password:reset 1 2 3

// Option
php artisan password:reset --ids=1 --ids=2 --ids=3
```
- array of arguments must be the last argument in command

### input description
- when we use --help with command it will give us description of that command
- it is very good thing to add description to your own command 
- so it there any programmer that use that command in the future he will know what it does
- make it like this
```php
protected $signature = 'password:reset
{userId : The ID of the user}
{--sendEmail : Whether to send user an
email}';
```
### using inputs
### argument() and arguments()
```php
// With definition "password:reset {userId}"

php artisan password:reset 5

// $this->arguments() returns this array
[
"command": "password:reset",
"userId": "5",
]

// $this->argument('userId') returns this string "5"
```
### option() and options()
- same as arguments()
```php
// With definition "password:reset {--userId=}"

php artisan password:reset --userId=5

// $this->options() returns this array

[
"userId" => "5",
"help" => false,
"quiet" => false,
"verbose" => false,
"version" => false,
"ansi" => false,
"no-ansi" => false,
"no-interaction" => false,
"env" => null,
]

// $this->option('userId') returns this string "5"
```
### prompts
- ways to get input from user
#### ask
```php
$email = $this->ask('What is your email address?');
```
#### secret
- ask for freedom text but hide it with ****
```php
$pass = $this->secret('What is your password?');
```
#### confirm
- yes/ no question
```php
if ($this->confirm('Do you want to truncate the tables?')) {
//
}
```
- return boolean value
#### anticipate()
- provide freedom text with auto complete signature
```php
 public function handle(): void
    {
        $album = $this->anticipate('What is the best album ever?', [
            "The Joshua Tree", "Pet Sounds", "What's Going On"
        ]);
        $this->info($album);
        $this->userMailer->build();
    }
```
#### choice
```php
    public function handle(): void
    {
        $winner = $this->choice(
            'Who is the best football team?',
            ['Gators', 'Wolverines'],
            0 // that mean first element
        );

        $this->info($winner);
        $this->userMailer->build();
    }

// or
$winner = $this->choice(
'Who is the best football team?',
['gators' => 'Gators', 'wolverines' => 'Wolverines'],
'gators' // you can pass key also
);
```
### output
- using info 
```php
$this->info('Welcome to Laravel!');
```
#### outputs and colors
- info => green
- error => red
- comment => orange
- question => highlighted
- line and newline => uncolored

#### table output
```php
$headers = ['Name', 'Email'];

$data = [
    ['Dhriti', 'dhriti@amrit.com'],
    ['Moses', 'moses@gutierez.com'],
];

// Or, you could get similar data from the database:
$data = App\User::all(['name', 'email'])->toArray();
$this->table($headers, $data);
```
```text
// Output:

+---------+--------------------+
| Name | Email |
+---------+--------------------+
| Dhriti | dhriti@amrit.com |
| Moses | moses@gutierez.com |
+---------+--------------------+
```
#### progress bar
```php
        $totalUnits = 100;
        $this->output->progressStart($totalUnits);
        for ($i = 0; $i < $totalUnits; $i++) {
            sleep(1);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
```
### writing closure passed commands
- as all classes in laravel,you can define your command as a closure function inside console.php 
```php
Artisan::command(
    'password:reset {userId} {--sendEmail}',
    function ($userId, $sendEmail) {
        $userId = $this->argument('userId');
        $this->info('UserResource id: ' . $userId);
    }
);
```
## Tinker
- we use it to interact with database and application
- it is a great tool for trying new ideas without creating code and files 
```text
$ php artisan tinker

>>> $user = new App\User;
=> App\User: {}
>>> $user->email = 'matt@mattstauffer.com';
=> "matt@mattstauffer.com"
>>> $user->password = bcrypt('superSecret');
=> "$2y$10$TWPGBC7e8d1bvJ1q5kv.VDUGfYDnE9gANl4mleuB3htIY2dxcQfQ5"
>>> $user->save();
=> true
```
## laravel dump server

## customizing generator stubs
- to can publish all stub files which are created by default in laravel and can modify them
```text
- run that command
php artisan stub:publish
```

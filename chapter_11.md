# Chapter 11 (The Container)
## A Quick Introduction to Dependency Injection
- to create object of UserMailer, i have to create object of Mailer first and inject it in UserMailer
```php
class UserMailer
{
protected $mailer;
public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    public function welcome($user)
    {
        return $this->mailer->mail($user->email, 'Welcome!');
    }
}
```
### Inversion of control
- dependency injection help us define the object in the constructor as we need it
- when i inject Mailer object in UserMailer, i can set configuration to use  Mailgun or Mandrill or Sendgrid in every mail

## Dependency Injection and Laravel
```php
$mailer = new MailgunMailer($mailgunKey, $mailgunSecret, $mailgunOptions);
$logger = new Logger($logPath, $minimumLogLevel);
$slack = new Slack($slackKey, $slackSecret, $channelName, $channelIcon);

$userMailer = new UserMailer($mailer, $logger, $slack);

$userMailer->welcome($user);
```
- do you see that code?
- it is a mess, every time i have to create object of UserMailer,
i will have to write all that code and create all those objects
- **that is the power of `service container`, let's see how it solves that mess.**

## The app() Global Helper
- it is used to get object of container
- we pass class name to app() function, and it will return object of that class

```php
$logger = app(Logger::class);

// is equal to

$logger = $app['App\test\Logger'];
```
## How the Container Is Wired
## Binding Classes to the Container
- here i tell the container if developer ask for an object of that class run that code
### Binding to a Closure
```php
// In any service provider (maybe LoggerServiceProvider)
public function register(): void
{
    $this->app->bind(Logger::class, function ($app) {
        return new Logger('\log\path\here', 'error');
    });
}

// that mean if user ask for Logger object, it will run that code in closure function
```
```php
$this->app->bind(UserMailer::class, function ($app) {
    return new UserMailer(
        $app->make(Mailer::class), // they don't have any dependency injection so i haven't to make any binds
        $app->make(Logger::class), 
        $app->make(Slack::class) 
    );
});
```
### Binding to Singletons, Aliases, and Instances
- singleton creates only one object of specific class which will be used over all that app
```php
$this->app->singleton(Mailer::class, function($app) {
    return new Mailer();
});

class Mailer
{
    public function __construct()
    {
        echo 'constructing mailer';
    }
    public function hello($name)
    {
        return 'Hello '.$name;
    }
}

public function test()
{
    $object = app(Mailer::class);
    echo $object->hello('Salma');
    $object2 = app(Mailer::class);
    echo $object2->hello('loma');
}

# the output here would be:
// constructing mailer => that is printed only once because only one instance is created
// Hello Salma
// Hello loma
```
### Binding a Concrete Instance to an Interface
```php
public function register(): void
{
    $this->app->bind(\Interfaces\Mailer::class, function () {
        return new MailgunMailer(...);
    });
}
```
- that achieve inversion of control
- i can typehint Mailer across the code, and if i want to change from Mailgun to Mandrill,
i can just change it in service provider once and all the code will work with no changes

### Contextual Binding


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

$logger = $app['App\Models\Logger'];
```


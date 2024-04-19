# notification 
## channels
- mail
- database
- nexmo (sms)
- broadcast (real-time)
- slack
```
 php artisan notifications:table
 php artisan make:notification NewProposalNotification
```
## via function
```php
public function via(object $notifiable): array // notifiable: person to whom i will send notification
{
    return ['database']; // channel i will send notification throw
    // there may be more than one channel
}
```
### i can make via dynamic according to user settings
```php
public function via(object $notifiable): array
{
    $via= ['database']; 
    if($notifiable->notify_mail)
    {
        $via[]='mail';
    }
    if($notifiable->notify_sms)
    {
        $via[]='nexmo';
    }
    return $via
}
```
- now you know why i pass $notifiable to via method

## to make configuration for any channel, i say ToNameOfChannel($notifiable)
- notice that ex:
```php
public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
                ->line('The introduction to the notification.')
                ->action('Notification Action', url('/'))
                ->line('Thank you for using our application!');
}
```
### toArray 
- works as configuration for all channels, if there is a channel i don't configure that toArray will be used
- to array is better to be used in case of database notification so i will not define toBroadcast and toDatabase, i will define toArray only

## use notifiable trait
- i should use notifiable trait with all models that will need notification

## to return notifications for specific user
- notifications return notifications ordered latest first 
```php
$notifications=$user->unreadNotifications()->take(10)->get(); // return only 10 notification
// or
$notifications=$user->notifications()->take(10)->get(); // return only 10 notification
// or
$notifications=$user->readNotifications()->take(10)->get(); // return only 10 notification
```

## to return number of unread notifications
```php
$count=$user->unreadNotifications()->conunt(); // return only 10 notification
```

## notice that difference
```php
$user->unreadNotifications // return collection
$user->unreadNotifications() // return query builder

// under is better because it count in db
// above count collection which have returned 
```

## mark notification as read
- make middleware
```
php artisan make:middleware MarkNotificationAsRead
```
```php
public function handle(Request $request, Closure $next): Response
    {
        $user=$request->user();
        $notify_id=$request->query('notify_id');
        if($notify_id)
        {
            $notification=$user->unreadNotifications()->find($notify_id);
            if($notification)
            {
                $notification->markAsRead();
            }
        }
        return $next($request);
    }
```
- never to forget to add it to kernel

# realtime
## add all configurations of pusher to .env file
## go to config/app.php
- uncomment that:
```php
App\Providers\BroadcastServiceProvider::class,
```
## install pusher server
```
composer require pusher/pusher-php-server
```
## make event
- that event take data and send it to pusher
```
php artisan make:event NewNotification
```
```php
public function broadcastOn(): array
{
    return [
        new PrivateChannel('new-notification'),
    ];
}
```
- to pass notification to event
```php
public function save_comment(Request $request)
    {
        Comment::create([
            'body'=> $request->post_content,
            'user_id'=>Auth::user()->id,
            'post_id'=>$request->post_id
        ]);

        $data=[
            'comment'=> $request->post_content,
            'user_id'=>Auth::user()->id,
            'post_id'=>$request->post_id
        ];
        event(new NewNotification($data));
        return redirect()->back()->with(['success'=>'post added successfully']);
    }
```

## fcm token (firebase cloud messaging)
[laravel-notification-channels](https://laravel-notification-channels.com/)
```
composer require laravel-notification-channels/fcm
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider" --tag=config
```
- do all as this [link](https://github.com/kreait/laravel-firebase)
- store FIREBASE_CREDENTIALS as json file in storage/app
- add all configurations in .env
- in config/app.php add that:
```php
 /*
  * Package Service Providers...
  */
 Kreait\Laravel\Firebase\ServiceProvider::class,
```
- create model and migration to store token for user devices
```
php artisan make:model DeviceToken -m
```
```php
 public function up(): void
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token');
            $table->string('device');
            $table->timestamps();
        });
    }
```
- add user relation to DeviceToken model
- in user model add those
```php
    public function deviceTokens():HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }
    public function routeNotificationForFcm($notification = null)
    {
        return $this->deviceTokens()->pluck('token')->toArray();
    }
```
- to send fcm notification
- as all notification
```php
User::find(1)->notify(new NewCategoryNotification(new Category()));
```
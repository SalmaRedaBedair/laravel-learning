## diffrence between valet, homestead and php artisan serve
- valet & homestead runs project using domain, we will not need to run command php artisan serve they handle that only what i do after installing valet or homestead is to put `{projectName}.test` in url and start testing project... but id i run php srtisan serve i will access project using `http://127.0.0.1:8000/`
- homestead need virtual machine 'Vagrant', it is the better choice if i want to test it as did in production level and also it is better for large files 
- valet is faster but it works only with small projects
- vassel work with docker
## lambo 
- is used to create new laravel project and commit it to github and also start working in server for test
- it works with valet only
## floders
1. app: models, controllers, commands and php domain code
2. bootstrap:files to boot every time
3. config: configration files like filesystems.php that we use to make disk to upload any thing in
4. database: migration & seeders
5. resources: front end files
6. routes: http and artisan routes
7. storage: caches, logs, and compiled system files live.
8. test: unit testing
9. vendor: Where Composer installs its dependencies.
## files
1. .editorconfig: Gives your IDE/text editor instructions about Laravel’s coding standars
2. .env and .env.example: enviornment variables
3. .gitignore and .gitattributes: git
4. artisan: allow us to write artisan commands
5. composer.json and composer.lock: define php-dependices.. json: user editable but lock in not
6. phpunit.xml: testing
## configration
- In a Laravel application, configuration settings are vital for defining how various services and components should behave. The core settings, including database connections, queue configurations, and mail settings, are stored in files within the `config` folder.

- One such configuration file is `config/services.php`, where you specify settings for various services your application may use. Each service has its own configuration options, such as API keys, secrets, or endpoints.

- For example, in `config/services.php`, you can configure services like SparkPost as follows:

```php
return [
    'sparkpost' => [
        'secret' => 'your_sparkpost_secret_key',
    ],
    // Other services and their configurations...
];
```

- to access the SparkPost secret key in a controller or any other place:
```php
$sparkpostSecret = config('services.sparkpost.secret');
```
## .evn
- APP_DEBUG=true: user will see debug error or not
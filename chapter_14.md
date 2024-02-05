# storage and retrieval
## local filesystems
1. S3
2. Rackspace
3. FTP

- S3 & Rackspace are provided by flysystems
- it is simple to add additional filesystem providers such as dropbox and webdev

## configure file access
- you can add configrations to disks in config/filesystems.php

# using storage facade
```php
Storage::get('file.jpg');
```
- that will access default disk whch is configured in config/filesystems.php
```php
 'default' => env('FILESYSTEM_DISK', 'local'),
// here default is local if i call Storage facade it will call local disk
```
## define file system disk `FILESYSTEM_DISK`
- in .env

```php
Storage::disk('s3')->get('file.jpg');
// if you define specific disk it will call it
// here it will call s3
```

# php artisan storage:link
- that will go and execute array links in config/filesystems.php
```php
'links' => [
        public_path('storage') => storage_path('app/public'),
        // that will create storage folder in public folder points to app/public
],
```
# upload files into public directly 
- sometimes i have no access to write commands on server
 - so i won't write that `php artisan storage:link`
 - so here it is recommended to make your own disk as shown down
- 
- here it is recommended to create your own disk
- only for simplicity to use syntax similar to of above and don't have to use move and those bad things

```php
'uploads' =>[
    'driver' => 'local',
    'root' => public_path('uploads'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
    'throw' => false,
],
```


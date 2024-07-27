# code base notes
```text
those are notes that i will apply to my code base while making it
```

## important notes while making auth of my code base
- don't add password to fillable of user
- while updating password use `forceFill`
- make end point to logout from current device
- make end point to logout from all devices
- make end point to logout from specific device(rather than current one)
- handle social login

## important notes in general for all project
- handle all exceptions in Exceptions/Handler.php
- make events for email and notifications
- make chat messages hashed
- use controller authorization and middleware authorization
- use commands and stubs to create your own module and remove it
- read from book while making stubs and commands and anything similar to can write more nice code
- php artisan publish:stub
- 
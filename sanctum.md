# Laravel Sanctum
```text
generate multiple tokens for each user
these tokens may have abilities/scopes
```
## How it works
### Api Token
- use tokens
- token stored in table `personal_access_tokens`
- token must be passed to request in header `Authorization: Bearer <token>`
### Single Page Applications (SPAs)
- use cookies
- use `web` guard

### how sanctum know if it will authenticate using token or cookie
- first sanctum check for any authentication cookie,
if none exists, it will check for Authorization header for token
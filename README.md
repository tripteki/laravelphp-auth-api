<h1 align="center">Auth-API</h1>

This package setup needed packages to build Auth RESTful API using Laravel Sanctum / Laravel JWT / Laravel Passport.

Getting Started
---

Installation :

```
composer require tripteki/laravelphp-auth-api
```

Usage
---

`php artisan auth-api:install <option>`

Option
---

- `sanctum` : As sanctum (Session-Token (stateful)), `composer require laravel/sanctum` is required.
- `jwt` : As jwt (Token (stateless)), `composer require tymon/jwt-auth` is required.
- `passport` : As passport (Token OAuth (stateful)), `composer require laravel/passport` is required.

Author
---

- Trip Teknologi ([@tripteki](https://linkedin.com/company/tripteki))
- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))

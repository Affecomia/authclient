# Young Ones Auth Client

This package allows you to manage authentication and authorization via the 
Young Ones authentication server.

Once installed, you can do stuff like 
```php
YoAuth::header(); // Returns the Authorization header to include in requests
YoAuth::token(); // Returns the token of authorization
```

It also provides a set of routes you can use to talk to the authentication
server, like logging in, refreshing and retrieving user information.

# Documentation, installation and usage instructions

## Installation
Package can be included in your Laravel application via composer.
```shell script
composer install yo/authclient
```
*Be sure* to have a valid ssh key registered to the correct gitlab 
repository.

Next, in your `composer.json` file, include
```json
{
  "extra": {
        "laravel": {
            "providers": [
                "YoAuth\\Providers\\YoAuthClientProvider"
            ]
        }
    },
    "autoload": {
        "psr-4": {
            "YoAuth\\": "packages/authclient/src"
        }
    }
}
```

Then, in your `config/app.php`, under `providers` key, define the next: 
```php
/*
 * Package Service Providers...
 */
\YoAuth\Providers\YoAuthClientProvider::class,
```

In `auth.php`, change the following:
```php
'guards' => [
    'web' => [
        'driver' => 'yoauth',
        ...
    ],

    'api' => [
        'driver' => 'yoauth',
        ...
    ],
],
```

Next, make sure to set the following variables in your `.env`:
```php
YO_AUTH_BASE_URI="http://127.0.0.1:18000"
YO_AUTH_CLIENT_ID="your_client_id"
YO_AUTH_CLIENT_SECRET="your_client_secret"
``` 
The `YO_AUTH_BASE_URI` being optional, since it is pre-configured in 
the package config file.

Be sure to have the oauth client registered in the authentication server,
to obtain the client id and secret.

> Note about configuration parameters: sometimes the Laravel installation
> can be bitchy about setting config parameters and clearing cache. If that
> happens, perform `php artisan vendor:publish` to extract the config file
> and perform a new 'cache clearing ritual'

## Usage

### Routes
The package will include a default set of endpoints, which can be 
used to 'talk' to the authentication server:

| Route | Url | Method | Outcome |
| ----- | --- | ------ | ------- |
| Login | /login | POST | Provides a set of tokens |
| Refresh | /refresh | POST | Refreshes your tokens |
| Logout | /logout | POST | Terminates your current authentication |
| User show | /users/{user} | GET | Gives you information about a user |

To provide full information about the routes, perform 
`php artisan route:list`, which also tells you about the middlewares
used.

### Guarding routes
To guard routes using the AuthClient, use the middleware `auth:api`:
```php
Route::get('/', 'Index')->middleware(['api', 'auth:api']);

// or

Route::middleware(['api', 'auth:api'])->group(static function () {
    // ... your routes
});
```

### Usage in controllers
Most of the requests need to be validated with the authentication server.
To make sure all requests meet the requirement, include the Authentication
header with every request. This Auth package provides some methods you can
use for this.

```php
// @var Guzzle\Client $client
$client = new Client([
    'base_uri' => 'https://my.beautiful.authserver',
    'headers' => YoAuth::header() // Returns Auth header
]);
$client->post(...)
```

Apart from that, you can also use `YoAuth::token()` to show the
current authentication token (in JWT format).

### Explanation
The package assigns a Middleware class to the routing system, which
extracts the `Bearer xxxxx` token provided in a request and stores this token in a
runtime session. This is cleared every request. A Facade class provides you the power to show the
token when needed.

## Testing
Work in progress. This will be a phpunit testsuite that can be run
via `./vendor/bin/phpunit`.

## Security
If you discover any security-related issues, please email wesley+packages@youngones.works
instead of using the issue tracker.


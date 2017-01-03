# Laravel Messager
A convenient way to handle messages between users in a simple way

## Installation:
First, install the package through Composer.

```php
composer require inani/messager
```

Then include the service provider inside `config/app.php`.

```php
'providers' => [
    ...
    Inani\Messager\MessagerServiceProvider::class,
    ...
];
```
Publish config and migrations

```
php artisan vendor:publish
```

## Setup a Model

```php
use Inani\Messager\Helpers\MessageAccessible;
class User extends Model
{
    use MessageAccessible;
    ...
}
```
## How to use
Writing in draft or Sending the message

![Writing in draft or Sending the message](https://cloud.githubusercontent.com/assets/12276076/21619061/f6a88d4c-d1e5-11e6-99ef-0849be17679b.png)

Handling messages

![Handling messages](https://cloud.githubusercontent.com/assets/12276076/21619106/2cbdb1dc-d1e6-11e6-9206-84131b6c162e.png)

# Laravel Messager
A convenient way to handle messages between users in a simple way

__Table of Contents__

1. [Installation](#installation)
2. [Setup a Model](#setup-a-model)
3. [Creating & Sending Messages](#creating--sending-messages)
    1. [Creating a message](#creating-a-message)
    2. [Sending the message](#sending-the-message)
    3. [Drafting a message](#drafting-a-message)
4. [Working with Messages](#how-to-use)
    1. [Getting messages between users](#getting-messages-between-users)
    2. [Unread messages](#unread-messages)
    3. [Draft messages](#draft-messages)

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

___

## Setup a Model

To setup a model all you have to do is add (and import) the `MessageAccessible` trait.

```php
use Inani\Messager\Helpers\MessageAccessible;
class User extends Model
{
    use MessageAccessible;
    ...
}
```

___

## Creating & sending Messages

### Creating a message
```php
$receiver = User::find(1); 

// Message Data
$messageData = [
	'content' => 'Hello all this is just a test', // the content of the message
	'to_id' => $receiver->getKey(), // Who should receive the message
];

list($message, $user) = App\User::createFromRequest($messageData);
```

### Sending the message
```php
$sender = User::find(2);

$sent = $sender->writes($message)
                ->to($user)
                ->send();
```

### Drafting a message
```php
$sender = User::find(2);

$draft = $sender->writes($message)
                ->to($user)
                ->draft()
                ->keep();
```

___
## Working with Messages
Once you've got messages you need to do something with them. 


### Getting messages between users
```php
// Users
$userA = App\User::find(1);
$userB = App\User::find(2);

// Get seen messages sent from UserB to UserA
$messages = $userA->received()->from($userB)->seen()->get();

// OR you can pass an array of IDs 
$messages = $userA->received()->from([2, 3, 4, 5])->seen()->get();
```

### Unread messages
```php
// Get unread messages from UserB to User A
$messages = $userA->received()->from($userB)->unSeen()->get();

// Marking them as read
$messages = $userA->received()->from($userB)->unSeen()->readThem();
```

### Sent messages
```php
// Get unread messages from UserA to UserB
$messages = $userA->sent()->to($userB)->get();

// OR you can pass an array of IDs
$messages = $userA->received()->to([2, 3, 4, 5)->get();
```


### Draft messages
```php
// Gets the draft messages for UserA
$messages = $userA->sent()->inDraft()->get().
```

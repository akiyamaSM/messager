(NOT MAINTAINED)
# Laravel Messager
A convenient way to handle messages between users in a simple way

__Table of Contents__

1. [Installation](#installation)
2. [Setup a Model](#setup-a-model)
3. [Creating & Sending Messages](#creating--sending-messages)
    1. [Creating a message](#creating-a-message)
    2. [Sending the message](#sending-the-message)
    3. [Responding the message](#responding-the-message)
    4. [Drafting a message](#drafting-a-message)
4. [Working with Messages](#working-with-messages)
    1. [Getting messages between users](#getting-messages-between-users)
    2. [Read messages](#read-messages)
    3. [Unread messages](#unread-messages)
    4. [Draft messages](#draft-messages)
5. [Tags](#tags)  
    1. [Create and Edit tags](#create-and-edit-tags)
    2. [Assign tag to message](#assign-tag-to-message)
    3. [Change and get tag of a message](#change-and-get-tag-of-a-message)
    4. [Remove a tag from a message](#remove-a-tag-from-a-message)

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
use Inani\Messager\Helpers\TagsCreator;
class User extends Model
{
    use MessageAccessible, TagsCreator;
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
		 
// send to multiple users the same message
// can execpt a user|array of users| array of ids| or array of users and ids
$sent = $sender->writes($message)
                 ->to($user)
		 ->cc([$user1, $user2])
		 ->cc([$user3->id, $user4->id])
                 ->send();
```

### Responding the message
```php
$sender = User::find(2);

$sent = $user->writes($newMessage)
                 ->to($sender)
		 ->responds($message)
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
### Read messages
```php
// Set the selected message(or id of messages as read)
$count = $userB->received()->select($message)->readThem();

```
### Unread messages
```php
// Get unread messages from UserB to User A
$messages = $userA->received()->from($userB)->unSeen()->get();

// Marking them as read
$messages = $userA->received()->from($userB)->unSeen()->readThem();

// check out if a conversation has new messages
$bool = $userA->received()->conversation($message)->hasNewMessages();

// Get the number of conversations that have new messages in it
$number = $userA->received()->unSeenConversations();
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
// Get the draft messages for UserA
$messages = $userA->sent()->inDraft()->get().
```
## Tags
You can tag (or structure your messages in different categories).

### Create and Edit tags
each user can make any number of tags.
```php
// create a new tag, $data can be (Tag instance, array, Request)
$tag = $userA->addNewTag($data);

// Modify the attributes of a tag
$user->tag($tag)->name("social")->color("#ffff")->apply();

```
### Assign tag to message
Once you have the message and the tag
```php
// you'll need the instance of user(to check if sender or receiver)
// $user and $tag can be ids or instance of User, Tag classes
$bool = $message->concerns($user)->putTag($tag);
```
### Change and get tag of a message
```php
// to change the tag just use the same method
$bool = $message->concerns($user)->putTag($tag);

// to get the tag of the message, null if not tagged
$tagOrNull = $message->concerns($user)->getTag();
// 
```

### Remove a tag from a message
```php
// To remove the tag from the message
$bool = $message->concerns($user)->removeTag();
```

# Session

PHP's session in a object-oriented version.

Main files: 
* Interface [phputil\Session](https://github.com/thiagodp/session/blob/master/lib/Session.php)
* Class [phputil\FileBasedSession](https://github.com/thiagodp/session/blob/master/lib/FileBasedSession.php)

This project uses [semantic versioning](http://semver.org/).

### Installation

```command
composer require phputil/session
```

### Example 1

Setting and getting a value from the session.

```php
$session = new phputil\FileBasedSession();
$session->start();

$session->put( 'user_name', $_POST[ 'user_name' ] ); // Set a value in the session
echo 'Hello, ', $session->get( 'user_name' ); // Get a value from the session
```

### Example 2

Setting session cookie name and cookie duration.

```php
$session = new phputil\FileBasedSession();
$session->setName( 'myapp' ); // (optional) "PHPSESSID" session cookie key becomes "myapp"
$session->setCookieParams( $lastOneDay = 60 * 60 * 24 ); // (optional) cookie will last one day
$session->start();
```

### Example 3

Swapping between sessions.

```php
$session = new phputil\FileBasedSession();

$session->start();
$savedId = $session->id();
$session->close();

// Opening another session
$session->id( $_GET[ 'another_id' ] );
$session->start(); // starts the session with id "another_id"
...
$session->close();

// Restoring the session with "savedId"
$session->id( $savedId );
$session->start();
```

### Example 4

Regenerating the session id.

```php
$session = new phputil\FileBasedSession();
$session->start();
$session->regenerateId( true ); // true means delete the old file
```

### Example 5

Destroying the session.

```php
$session = new phputil\FileBasedSession();
$session->start();
$session->destroy();
```

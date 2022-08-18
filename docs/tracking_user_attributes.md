# Tracking User Attributes

## Default Braze attributes

For all the available purchase properties see `Lingoda\BrazeBundle\Api\Object\Property\UserAttributesProperties`

```php
use Lingoda\BrazeBundle\Api\Object\ExternalId;use Lingoda\BrazeBundle\Api\Object\UserAttributes;

$userAttributes = new UserAttributes([
    'external_id' => new ExternalId('user-id'),
    'first_name' => 'John',
    'last_name' => 'Doe',
]);

try {
    $brazeApi->users()->trackAttributes($userAttributes);
} catch (BrazeApiException $e) {
    // handle exception
}
```

## Updating Social attributes

```php
use Lingoda\BrazeBundle\Api\Object\Facebook;
use Lingoda\BrazeBundle\Api\Object\Twitter;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;

$userAttributes = new UserAttributes([
    'external_id' => new ExternalId('user-id'),
    'facebook' => new Facebook([ // check \Lingoda\BrazeBundle\Api\Object\Property\FacebookProperties for all available properties
        'id' => 'facebook-id',
        'likes' => [],
        'num_friends' => 1200,
    ]),
    'twitter' => new Twitter([ // check \Lingoda\BrazeBundle\Api\Object\Property\TwitterProperties for all available properties
        'id' => 1212321,
        ...
    ]),
]);
```

### Updating Current Location

```php
use Lingoda\BrazeBundle\Api\Object\Location;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;

$userAttributes = new UserAttributes([
    'external_id' => new ExternalId('user-id'),
    'current_location' => new Location(42.5645, 46.056),
]);
```

### Tracking push tokens

```php
use Lingoda\BrazeBundle\Api\Object\PushToken;use Lingoda\BrazeBundle\Api\Object\UserAttributes;

$userAttributes = new UserAttributes([
    'external_id' => new ExternalId('user-id'),
    'push_tokens' => [
        new PushToken([
            'app_id' => 'App Identifier',
            'token' => 'abcd',
            'device_id' => 'optional_field_value',
        ]),
        // ...
    ],
]);
```

## Custom attributes

```php
use Lingoda\BrazeBundle\Api\Object\UserAttributes;

$userAttributes = new UserAttributes([
    'external_id' => new ExternalId('user-id'),
    'email' => 'john@example.com',
]);
$userAttributes->setCustomUserAttribute('my_custom_int', 12);
```

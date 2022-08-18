# Create New User Alias

## Simple alias creation

```php
use Lingoda\BrazeBundle\Api\Object\UserAlias;

try {
    $brazeApi->users()->addAlias(
        new UserAlias('my_alias_name', 'my_alias_label')
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

## Create alias for external id

```php
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;

try {
    $brazeApi->users()->addAlias(
        new UserAlias('my_alias_name', 'my_alias_label', new ExternalId('user-id'))
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

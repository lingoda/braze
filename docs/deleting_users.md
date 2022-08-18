# User Delete Endpoint

## Delete by external ids

```php
use Lingoda\BrazeBundle\Api\Object\ExternalId;
try {
    $brazeApi->users()->deleteExternalIds(
        new ExternalId('user-id-1'),
        new ExternalId('user-id-2')
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

## Delete by braze ids

```php
use Lingoda\BrazeBundle\Api\Object\BrazeId;
try {
    $brazeApi->users()->deleteBrazeids(
        new BrazeId('braze-id')
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

## Delete by User Alias

```php
use Lingoda\BrazeBundle\Api\Object\UserAlias;
try {
    $brazeApi->users()->deleteBrazeids(
        new UserAlias('user_email', 'john@example.com')
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

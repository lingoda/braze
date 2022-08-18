# Identify Users

```php
use Lingoda\BrazeBundle\Api\Object\AliasToIdentify;use Lingoda\BrazeBundle\Api\Object\ExternalId;use Lingoda\BrazeBundle\Api\Object\UserAlias;
try {
    $brazeApi->users()->identify(
        new AliasToIdentify(
            new ExternalId('user-id'),
            new UserAlias(
                'my_alias_name',
                'my_alias_label'
            )
        )
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

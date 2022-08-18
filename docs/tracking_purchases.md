# Tracking Purchases

## Simple purchase

For all the available purchase properties see `Lingoda\BrazeBundle\Api\Object\Property\PurchaseProperties`

```php
use Carbon\CarbonImmutable;use Lingoda\BrazeBundle\Api\Object\AppId;
use Lingoda\BrazeBundle\Api\Object\Purchase;

$purchase = new Purchase([
    'app_id' => new AppId('app-id-value'),
    'product_id' => 'My sample product',
    'currency' => 'EUR',
    'price' => 10.00,
    'time' => CarbonImmutable::now(),
]);

try {
    $brazeApi->users()->trackPurchases($purchase);
} catch (BrazeApiException $e) {
    // handle exception
}
```

## Purchase with custom attributes

```php
$purchase = new Purchase([
    'app_id' => new AppId('app-id-value'),
    'product_id' => 'My sample product',
    'currency' => 'EUR',
    'price' => 10.00,
    'time' => CarbonImmutable::now(),
    'properties' => new Properties([
       'custom_integer_property' => 12,
       'custom_float_property' => 0.3,
       'custom_boolean_property' = true,
       'custom_date_property' => CarbonImmutable::now(),
       'custom_array' => ['foo', 'bar'],
    ])
]);
```

### Tracking multiple purchases

```php

$purchase1 = new Purchase([...]);
$purchase2 = new Purchase([...]);

try {
    $brazeApi->users()->trackPurchases(
        $purchase1,
        $purchase2
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

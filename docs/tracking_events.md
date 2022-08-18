# Tracking User Events

## Simple event

For all the available event properties see `Lingoda\BrazeBundle\Api\Object\Property\EventProperties`

```php
# tracking user events
use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Exception\BrazeApiException;
use Lingoda\BrazeBundle\Api\Object\AppId;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;

// by default it will set "time" attribute to NOW
$event = new Event([
    'external_id' => new ExternalId('external-id-value'),
    'name' => 'event-name',
]);

try {
    $brazeApi->users()->trackEvents($event);
} catch (BrazeApiException $e) {
    // handle exception
}

// Event with additional attributes

$event = new Event([
    'external_id' => new ExternalId('external-id-value'),
    'name' => 'event-name',
    'time' => new CarbonImmutable('+1 day'),
    'app_id' => new AppId('application-id-value'),
])
```

## Events with custom properties

```php
use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Object\Properties;

$event = new Event([
    'external_id' => new ExternalId('external-id-value'),
    'name' => 'event-name',
    'properties' => new Properties([
       'custom_integer_property' => 12,
       'custom_float_property' => 0.3,
       'custom_boolean_property' = true,
       'custom_date_property' => CarbonImmutable::now(),
       'custom_array' => ['foo', 'bar'],
    ])
]);
```

## Multiple events tracking

```php
$event1 = new Event([
    'external_id' => new ExternalId('external-id-value'),
    'name' => 'my-custom-event-1',
]);

$event2 = new Event([
    'external_id' => new ExternalId('external-id-value'),
    'name' => 'my-custom-event-2',
])

try {
    $brazeApi->users()->trackEvents(
        $event1,
        $event2,
    );
} catch (BrazeApiException $e) {
    // handle exception
}
```

## Tracking through track endpoint

```php
use Lingoda\BrazeBundle\Api\Request\TrackUserDataRequest;

$event = new Event([
    'external_id' => new ExternalId('external-id-value'),
    'name' => 'my-custom-event',
]);

try {
    $brazeApi->users()->track(TrackUserDataRequest::withEvents([$event]));
} catch (BrazeApiException $e) {
    // handle exception
}
```

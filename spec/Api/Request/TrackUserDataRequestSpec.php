<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Request;

use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\AppId;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Purchase;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Api\Request\TrackUserDataRequest;
use PhpSpec\ObjectBehavior;

class TrackUserDataRequestSpec extends ObjectBehavior
{
    function let(Event $event, UserAttributes $userAttributes, Purchase $purchase)
    {
        $this->beConstructedWith([
            'attributes' => [$userAttributes],
            'events' => [$event],
            'purchases' => [$purchase],
        ]);
    }

    function it_is_initializable(Event $event, UserAttributes $userAttributes, Purchase $purchase)
    {
        $this->shouldHaveType(TrackUserDataRequest::class);
        $this->getAttributes()->shouldBeEqualTo([$userAttributes]);
        $this->getEvents()->shouldBeEqualTo([$event]);
        $this->getPurchases()->shouldBeEqualTo([$purchase]);
        $this->hasAttributes()->shouldBeEqualTo(true);
        $this->hasEvents()->shouldBeEqualTo(true);
        $this->hasPurchases()->shouldBeEqualTo(true);
    }

    function it_throws_exception_whith_missing_parameters()
    {
        $this->beConstructedWith([]);
        $this
            ->shouldThrow(new InvalidArgumentException('At least one of the parameters "attributes", "events", "purchases" should be defined'))
            ->duringInstantiation()
        ;
    }

    function it_throws_exception_if_attributes_limit_reached()
    {
        $attributes = array_fill(0, 76, new UserAttributes([
            'external_id' => new ExternalId('id'),
            'email' => 'test@lingoda.com',
        ]));

        $this->beConstructedWith([
            'attributes' => $attributes,
        ]);

        $this
            ->shouldThrow(new InvalidArgumentException('Each request can contain up to 75 attributes'))
            ->duringInstantiation()
        ;
    }

    function it_throws_exception_if_events_limit_reached()
    {
        $events = array_fill(0, 76, new Event([
            'external_id' => new ExternalId('id'),
            'name' => 'custom_event',
        ]));

        $this->beConstructedWith([
            'events' => $events,
        ]);

        $this
            ->shouldThrow(new InvalidArgumentException('Each request can contain up to 75 events'))
            ->duringInstantiation()
        ;
    }

    function it_throws_exception_if_purchases_limit_reached()
    {
        $purchases = array_fill(0, 76, new Purchase([
            'external_id' => new ExternalId('id'),
            'app_id' => new AppId('930FB07C-CE36-4282-BE16-15A00F657CD2'),
            'product_id' => 'product_id',
            'price' => 10.0,
            'currency' => 'EUR',
        ]));

        $this->beConstructedWith([
            'purchases' => $purchases,
        ]);

        $this
            ->shouldThrow(new InvalidArgumentException('Each request can contain up to 75 purchases'))
            ->duringInstantiation()
        ;
    }

    function it_can_create_with_events(Event $event)
    {
        $this->beConstructedThrough('withEvents', [[$event]]);
        $this->shouldHaveType(TrackUserDataRequest::class);
        $this->hasEvents()->shouldBeEqualTo(true);
        $this->getEvents()->shouldBeLike([
            $event,
        ]);
    }

    function it_can_create_with_attributes(UserAttributes $attributes)
    {
        $this->beConstructedThrough('withAttributes', [[$attributes]]);
        $this->shouldHaveType(TrackUserDataRequest::class);
        $this->hasAttributes()->shouldBeEqualTo(true);
        $this->getAttributes()->shouldBeLike([
            $attributes,
        ]);
    }

    function it_can_create_with_purchases(Purchase $purchase)
    {
        $this->beConstructedThrough('withPurchases', [[$purchase]]);
        $this->shouldHaveType(TrackUserDataRequest::class);
        $this->hasPurchases()->shouldBeEqualTo(true);
        $this->getPurchases()->shouldBeLike([
            $purchase,
        ]);
    }
}

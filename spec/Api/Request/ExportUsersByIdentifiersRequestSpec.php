<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Request;

use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Request\ExportUsersByIdentifiersRequest;
use PhpSpec\ObjectBehavior;

class ExportUsersByIdentifiersRequestSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['braze_id' => new BrazeId('braze-id')]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ExportUsersByIdentifiersRequest::class);
        $this->getOptions()->shouldIterateLike([
            'braze_id' => new BrazeId('braze-id'),
        ]);
    }

    function it_can_not_be_initialized_without_param()
    {
        $this->beConstructedWith([]);
        $this
            ->shouldThrow(new \InvalidArgumentException(
                'At least one of the parameters "external_ids", "user_aliases", "device_id", "braze_id", "email_address" should be defined'
            ))
            ->duringInstantiation()
        ;
    }

    function it_can_not_be_initialized_above_external_id_limit()
    {
        $externalIds = array_map(static fn ($id) => new ExternalId((string) $id), range(1, 51));
        $this->beConstructedWith(['external_ids' => $externalIds]);

        $this
            ->shouldThrow(new \InvalidArgumentException('The option "external_ids" with value array is invalid.'))
            ->duringInstantiation()
        ;
    }

    function it_can_not_be_initialized_above_user_aliases_limit()
    {
        $userAliases = array_map(static fn ($id) => new UserAlias('aliasName', (string) $id), range(1, 51));
        $this->beConstructedWith(['user_aliases' => $userAliases]);

        $this
            ->shouldThrow(new \InvalidArgumentException('The option "user_aliases" with value array is invalid.'))
            ->duringInstantiation()
        ;
    }

    function it_can_request_with_external_ids()
    {
        $externalIds = array_map(static fn ($id) => new ExternalId((string) $id), range(1, 2));
        $this->beConstructedWith(['external_ids' => $externalIds]);

        $this->getOptions()->shouldIterateLike([
            'external_ids' => [
                new ExternalId('1'),
                new ExternalId('2'),
            ],
        ]);
    }

    function it_can_request_custom_fields_to_export()
    {
        $this->beConstructedWith([
            'email_address' => 'customer@example.com',
            'fields_to_export' => ['external_id'],
        ]);
        $this->getOptions()->shouldIterateLike([
            'email_address' => 'customer@example.com',
            'fields_to_export' => ['external_id'],
        ]);
    }

    function it_can_request_by_device_id()
    {
        $this->beConstructedWith([
            'device_id' => 'device-id',
        ]);
        $this->getOptions()->shouldIterateLike([
            'device_id' => 'device-id',
        ]);
    }

    function it_can_request_by_phone()
    {
        $this->beConstructedWith([
            'phone' => 'phone',
        ]);
        $this->getOptions()->shouldIterateLike([
            'phone' => 'phone',
        ]);
    }
}

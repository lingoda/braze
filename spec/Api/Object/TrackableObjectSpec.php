<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use Lingoda\BrazeBundle\Api\Exception\LogicException;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\TrackableObject;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use PhpSpec\ObjectBehavior;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

class TrackableObjectSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'external_id' => new ExternalId('external-id'),
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TrackableObject::class);
        $this->getOptions()->shouldHaveKey('external_id');
    }

    function it_cannot_be_initialized_without_an_identifier()
    {
        $this->beConstructedWith([]);
        $this
            ->shouldThrow(new LogicException('One of "external_id" or "user_alias" or "braze_id" is required'))
            ->duringInstantiation()
        ;
    }

    function it_cannot_be_initialized_with_multiple_identifiers()
    {
        $this->beConstructedWith([
            'external_id' => new ExternalId('external-id'),
            'braze_id' => new BrazeId('braze-id'),
        ]);
        $this
            ->shouldThrow(new LogicException('Too many identifiers. Use one of "external_id" or "user_alias" or "braze_id" identifier'))
            ->duringInstantiation()
        ;
    }

    function it_turns_off_updated_only_mode_whith_user_alias_request()
    {
        $userAlias = new UserAlias('name', 'label');
        $this->beConstructedWith([
            'user_alias' => $userAlias,
            '_update_existing_only' => true,
        ]);
        $this->getOptions()->shouldBeEqualTo([
            'user_alias' => $userAlias,
            '_update_existing_only' => false,
        ]);
    }

    function it_can_turn_on_update_only_mode_with_external_id()
    {
        $externalId = new ExternalId('external-id');
        $this->beConstructedWith([
            'external_id' => $externalId,
            '_update_existing_only' => true,
        ]);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            '_update_existing_only' => true,
        ]);
    }

    function it_can_turn_off_update_only_mode_with_external_id()
    {
        $externalId = new ExternalId('external-id');
        $this->beConstructedWith([
            'external_id' => $externalId,
            '_update_existing_only' => false,
        ]);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            '_update_existing_only' => false,
        ]);
    }

    function it_can_turn_on_update_only_mode_with_braze_id()
    {
        $brazeId = new BrazeId('braze-id');
        $this->beConstructedWith([
            'braze_id' => $brazeId,
            '_update_existing_only' => true,
        ]);
        $this->getOptions()->shouldBeEqualTo([
            'braze_id' => $brazeId,
            '_update_existing_only' => true,
        ]);
    }

    function it_can_turn_off_update_only_mode_with_braze_id()
    {
        $brazeId = new BrazeId('braze-id');
        $this->beConstructedWith([
            'braze_id' => $brazeId,
            '_update_existing_only' => false,
        ]);
        $this->getOptions()->shouldBeEqualTo([
            'braze_id' => $brazeId,
            '_update_existing_only' => false,
        ]);
    }

    function it_cannot_delete_external_id()
    {
        $this->beConstructedWith([
            'external_id' => null,
        ]);

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_cannot_delete_braze_id()
    {
        $this->beConstructedWith([
            'braze_id' => null,
        ]);

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_cannot_delete_user_alias()
    {
        $this->beConstructedWith([
            'user_alias' => null,
        ]);

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }
}

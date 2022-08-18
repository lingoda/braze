<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use PhpSpec\ObjectBehavior;

class UserAliasSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('example_name', 'example_label');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserAlias::class);
        $this->getAliasLabel()->shouldBeEqualTo('example_label');
        $this->getAliasName()->shouldBeEqualTo('example_name');
        $this->getExternalId()->shouldBeNull();
        $this->getValue()->shouldBeEqualTo([
            'alias_name' => 'example_name',
            'alias_label' => 'example_label',
        ]);
    }

    function it_is_initializable_with_external_id()
    {
        $externalId = new ExternalId('external-id');
        $this->beConstructedWith('example_name', 'example_label', $externalId);

        $this->shouldHaveType(UserAlias::class);
        $this->getAliasLabel()->shouldBeEqualTo('example_label');
        $this->getAliasName()->shouldBeEqualTo('example_name');
        $this->getExternalId()->shouldBe($externalId);
        $this->getValue()->shouldBeEqualTo([
            'alias_name' => 'example_name',
            'alias_label' => 'example_label',
            'external_id' => $externalId,
        ]);
    }
}

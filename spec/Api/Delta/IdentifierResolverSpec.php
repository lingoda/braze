<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Delta;

use Lingoda\BrazeBundle\Api\Delta\IdentifierResolver;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IdentifierResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IdentifierResolver::class);
    }

    function it_can_resolve_external_id()
    {
        $externalId = new ExternalId('1');
        $userAttributes = new UserAttributes(['external_id' => $externalId]);

        $this->resolve($userAttributes)->shouldBeEqualTo('1');
    }

    function it_can_resolve_braze_id()
    {
        $brazeId = new BrazeId('1');
        $userAttributes = new UserAttributes(['braze_id' => $brazeId]);

        $this->resolve($userAttributes)->shouldBeEqualTo('1');
    }

    function it_can_resolve_user_alias()
    {
        $userAlias = new UserAlias('user_email', 'test@lingoda.com');
        $userAttributes = new UserAttributes(['user_alias' => $userAlias]);

        $this->resolve($userAttributes)->shouldBeEqualTo('user_email:test@lingoda.com');
    }

    function it_will_return_null_with_unsupported_identifier(UserAttributes $userAttributes)
    {
        $userAttributes->hasOption(Argument::cetera())->willReturn(false);

        $this->resolve($userAttributes)->shouldBeNull();
    }
}

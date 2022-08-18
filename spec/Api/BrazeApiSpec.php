<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api;

use Lingoda\BrazeBundle\Api\BrazeApi;
use Lingoda\BrazeBundle\Api\Endpoint\Export;
use Lingoda\BrazeBundle\Api\Endpoint\Messaging;
use Lingoda\BrazeBundle\Api\Endpoint\Users;
use PhpSpec\ObjectBehavior;

class BrazeApiSpec extends ObjectBehavior
{
    function let(Users $users, Messaging $messaging, Export $export)
    {
        $this->beConstructedWith($users, $messaging, $export);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BrazeApi::class);
    }
}

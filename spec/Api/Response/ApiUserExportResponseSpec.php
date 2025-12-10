<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\Object\Export\UserData\User;
use Lingoda\BrazeBundle\Api\Response\ApiUserExportResponse;
use PhpSpec\ObjectBehavior;

class ApiUserExportResponseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $errors = ['error'];
        $users = [['external_id' => '123']];

        $this->beConstructedWith('success', $users, $errors);
        $this->shouldHaveType(ApiUserExportResponse::class);
        $this->getMessage()->shouldBeEqualTo('success');

        $this->getErrors()->shouldBe($errors);
        $this->getUsersRaw()->shouldBe($users);
        $this->getUsers()->shouldBeLike(array_map(User::fromArray(...), $users));
    }
}

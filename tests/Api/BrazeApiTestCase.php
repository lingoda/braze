<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api;

use Lingoda\BrazeBundle\Api\BrazeApiInterface;
use Lingoda\BrazeBundle\Api\Endpoint\Messaging;
use Lingoda\BrazeBundle\Api\Endpoint\Users;
use Lingoda\BrazeBundle\Api\Object\AppId;
use Lingoda\BrazeBundle\Tests\BrazeAssertionsTrait;
use Lingoda\BrazeBundle\Tests\MockClient\MockClientTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BrazeApiTestCase extends KernelTestCase
{
    use BrazeAssertionsTrait;
    use MockClientTrait;

    protected const MOCK_APP_ID = '8f1d7766-5640-48bc-aadd-ced70ce13479';
    protected const EXTERNAL_ID = 'test-botond-local';

    protected AppId $appId;
    protected BrazeApiInterface $brazeApi;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $brazeApi = self::getContainer()->get('test.braze_api');
        self::assertInstanceOf(BrazeApiInterface::class, $brazeApi);
        $this->brazeApi = $brazeApi;

        /** @var string $appId */
        $appId = self::getContainer()->getParameter('app_id');
        $this->appId = new AppId($appId);
    }

    protected function mockHttpClient(): void
    {
        $brazeApi =self::getContainer()->get('test.mock.braze_api');
        self::assertInstanceOf(BrazeApiInterface::class, $brazeApi);
        $this->brazeApi = $brazeApi;
    }

    protected function appIdAsString(): string
    {
        return (string) $this->appId;
    }

    protected function users(): Users
    {
        return $this->brazeApi->users();
    }

    protected function messaging(): Messaging
    {
        return $this->brazeApi->messaging();
    }
}

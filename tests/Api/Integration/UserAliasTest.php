<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Integration;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Tests\Api\BrazeApiTestCase;

/**
 * @group braze-integration
 */
final class UserAliasTest extends BrazeApiTestCase
{
    private const TEST_EMAIL = 'test-integration-with-user-alias@lingoda.com';

    public function testCanCreateUserAlias(): void
    {
        $externalId = new ExternalId('test-integration-with-user-alias');

        $this->lastResponse = $this->users()->trackAttributes(new UserAttributes([
            'external_id' => $externalId,
            'email' => self::TEST_EMAIL,
        ]));
        $this->assertAttributesProcessed();

        $this->lastResponse = $this->users()->addAlias(new UserAlias(
            'user_email',
            self::TEST_EMAIL,
            $externalId
        ));
        $this->assertResponseSuccessful();

        $this->lastResponse = $this->users()->trackEvents(new Event([
            'user_alias' => new UserAlias('user_email', self::TEST_EMAIL),
            'app_id' => $this->appId,
            'time' => CarbonImmutable::now(),
            'name' => 'custom-event',
        ]));
        $this->assertEventsProcessed();
    }
}

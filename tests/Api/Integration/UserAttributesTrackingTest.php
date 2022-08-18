<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Integration;

use Lingoda\BrazeBundle\Api\Constants\Gender;
use Lingoda\BrazeBundle\Api\Constants\LanguageCodes;
use Lingoda\BrazeBundle\Api\Constants\SubscriptionType;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Location;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Tests\Api\BrazeApiTestCase;

/**
 * @group braze-integration
 */
final class UserAttributesTrackingTest extends BrazeApiTestCase
{
    public function testItCanTrackUserAttributesWithExternalId(): void
    {
        $userAttributes = new UserAttributes([
            'external_id' => new ExternalId('test-integration-user-attributes'),
            'email' => 'test-integration-user-attributes@linguando.com',
            'first_name' => 'John',
            'last_name' => 'Tester',
            'gender' => Gender::OTHER,
            'dob' => '1970-01-01',
            'language' => LanguageCodes::ENGLISH,
            'current_location' => new Location(52.5079376, 13.3925277),
            'country' => 'DE',
            'email_subscribe' => SubscriptionType::UNSUBSCRIBED,
            'home_city' => 'Berlin',
            'phone' => '112',
            'time_zone' => 'Europe/Berlin',
            'email_open_tracking_disabled' => false,
            'email_click_tracking_disabled' => false,
            'image_url' => 'https://i.pravatar.cc/300?u=a042581f4e29026704d',
        ]);

        $this->lastResponse = $this->users()->trackAttributes($userAttributes);
        $this->assertResponseSuccessful();
    }
}

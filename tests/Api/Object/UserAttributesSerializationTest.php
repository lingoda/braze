<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Constants\Gender;
use Lingoda\BrazeBundle\Api\Constants\LanguageCodes;
use Lingoda\BrazeBundle\Api\Constants\SubscriptionType;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Facebook;
use Lingoda\BrazeBundle\Api\Object\Location;
use Lingoda\BrazeBundle\Api\Object\Twitter;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class UserAttributesSerializationTest extends SerializationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        CarbonImmutable::setTestNow('2021-08-19 19:26');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        CarbonImmutable::setTestNow();
    }

    /**
     * @dataProvider serializationData
     */
    public function testSerialization(UserAttributes $userAttributes, string $expectedJson): void
    {
        self::assertSame($expectedJson, $this->json($userAttributes));
    }

    /**
     * @return iterable<string, array{UserAttributes, string}>
     */
    public function serializationData(): iterable
    {
        $externalId = new ExternalId('external-id');
        $brazeId = new BrazeId('braze-id');
        $userAlias = new UserAlias('alias', 'label');
        $now = CarbonImmutable::now();

        yield 'with-external-id' => [
            new UserAttributes(['external_id' => $externalId]),
            '{"external_id":"external-id"}',
        ];

        yield 'with-braze-id' => [
            new UserAttributes(['braze_id' => $brazeId]),
            '{"braze_id":"braze-id"}',
        ];

        yield 'with-user-alias' => [
            new UserAttributes(['user_alias' => $userAlias]),
            '{"user_alias":{"alias_name":"alias","alias_label":"label"}}',
        ];

        yield 'with-email' => [
            new UserAttributes(['external_id' => $externalId, 'email' => 'test@lingoda.com']),
            '{"external_id":"external-id","email":"test@lingoda.com"}',
        ];

        yield 'with-all-properties' => [
            new UserAttributes([
                'external_id' => $externalId,
                'country' => 'DE',
                'current_location' => new Location(12.1, 13.1),
                'date_of_first_session' => $now,
                'date_of_last_session' => $now,
                'dob' => '1970-01-01',
                'email' => 'test@lingoda.com',
                'email_subscribe' => SubscriptionType::OPTED_IN,
                'email_open_tracking_disabled' => true,
                'email_click_tracking_disabled' => true,
                'facebook' => new Facebook([
                    'id' => 'facebook-id',
                    'likes' => [
                        'like1',
                        'like2',
                    ],
                    'num_friends' => 1,
                ]),
                'first_name' => 'John',
                'gender' => Gender::OTHER,
                'home_city' => 'Berlin',
                'image_url' => 'https://linguando.com',
                'language' => LanguageCodes::GERMAN,
                'last_name' => 'Doe',
                'marked_email_as_spam_at' => $now,
                'phone' => '34455345',
                'push_subscribe' => SubscriptionType::UNSUBSCRIBED, // TODO add push_tokens
                'time_zone' => 'Europe\Berlin',
                'twitter' => new Twitter([
                    'id' => 123,
                    'screen_name' => 'screen-name',
                    'followers_count' => 1,
                    'friends_count' => 2,
                    'statuses_count' => 3,
                ]),
            ]),
            (string) json_encode([
                'external_id' => 'external-id',
                'country' => 'DE',
                'current_location' => [
                    'latitude' => 12.1,
                    'longitude' => 13.1,
                ],
                'date_of_first_session' => $now->format(CarbonImmutable::ATOM),
                'date_of_last_session' => $now->format(CarbonImmutable::ATOM),
                'dob' => '1970-01-01',
                'email' => 'test@lingoda.com',
                'email_subscribe' => SubscriptionType::OPTED_IN,
                'email_open_tracking_disabled' => true,
                'email_click_tracking_disabled' => true,
                'facebook' => [
                    'id' => 'facebook-id',
                    'likes' => [
                        'like1',
                        'like2',
                    ],
                    'num_friends' => 1,
                ],
                'first_name' => 'John',
                'gender' => Gender::OTHER,
                'home_city' => 'Berlin',
                'image_url' => 'https://linguando.com',
                'language' => LanguageCodes::GERMAN,
                'last_name' => 'Doe',
                'marked_email_as_spam_at' => $now->format(CarbonImmutable::ATOM),
                'phone' => '34455345',
                'push_subscribe' => SubscriptionType::UNSUBSCRIBED, // TODO add push_tokens
                'time_zone' => 'Europe\Berlin',
                'twitter' => [
                    'id' => 123,
                    'screen_name' => 'screen-name',
                    'followers_count' => 1,
                    'friends_count' => 2,
                    'statuses_count' => 3,
                ],
            ]),
        ];
    }
}

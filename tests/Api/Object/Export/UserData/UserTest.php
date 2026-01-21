<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use DateTimeImmutable;
use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\App;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Campaign;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Canvas;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Card;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\CustomEvent;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Device;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Gender;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Purchase;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\PushToken;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\User;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\User
 */
final class UserTest extends TestCase
{
    public function testFromArrayCreatesUserFromFullApiResponse(): void
    {
        // Setup
        $data = $this->getFullApiResponseData();

        // Execution
        $user = User::fromArray($data);

        // Assertion - basic fields
        self::assertNotNull($user->createdAt);
        self::assertSame('2020-07-10', $user->createdAt->format('Y-m-d'));
        self::assertSame('A8i3mkd99', $user->externalId);
        self::assertSame('5fbd99bac125ca40511f2cb1', $user->brazeId);
        self::assertSame(2365, $user->randomBucket);
        self::assertSame('Jane', $user->firstName);
        self::assertSame('Doe', $user->lastName);
        self::assertSame('[email protected]', $user->email);
        self::assertNotNull($user->dob);
        self::assertSame('1980-12-21', $user->dob->format('Y-m-d'));
        self::assertSame('Chicago', $user->homeCity);
        self::assertSame('US', $user->country);
        self::assertSame('+442071838750', $user->phone);
        self::assertSame('en', $user->language);
        self::assertSame('Eastern Time (US & Canada)', $user->timeZone);
        self::assertSame(Gender::Female, $user->gender);
        self::assertSame([41.84157636433568, -87.83520818508256], $user->lastCoordinates);
        self::assertSame('opted_in', $user->pushSubscribe);
        self::assertNotNull($user->pushOptedInAt);
        self::assertSame('2020-01-26', $user->pushOptedInAt->format('Y-m-d'));
        self::assertSame('subscribed', $user->emailSubscribe);
        self::assertSame(65.0, $user->totalRevenue);

        // Assertion - nested objects
        self::assertNotNull($user->userAliases);
        self::assertCount(1, $user->userAliases);
        self::assertInstanceOf(UserAlias::class, $user->userAliases[0]);
        self::assertNotNull($user->customEvents);
        self::assertCount(1, $user->customEvents);
        self::assertInstanceOf(CustomEvent::class, $user->customEvents[0]);
        self::assertNotNull($user->purchases);
        self::assertCount(1, $user->purchases);
        self::assertInstanceOf(Purchase::class, $user->purchases[0]);
        self::assertNotNull($user->devices);
        self::assertCount(1, $user->devices);
        self::assertInstanceOf(Device::class, $user->devices[0]);
        self::assertNotNull($user->pushTokens);
        self::assertCount(1, $user->pushTokens);
        self::assertInstanceOf(PushToken::class, $user->pushTokens[0]);
        self::assertNotNull($user->apps);
        self::assertCount(1, $user->apps);
        self::assertInstanceOf(App::class, $user->apps[0]);
        self::assertNotNull($user->campaignsReceived);
        self::assertCount(1, $user->campaignsReceived);
        self::assertInstanceOf(Campaign::class, $user->campaignsReceived[0]);
        self::assertNotNull($user->canvasesReceived);
        self::assertCount(1, $user->canvasesReceived);
        self::assertInstanceOf(Canvas::class, $user->canvasesReceived[0]);
        self::assertNotNull($user->cardsClicked);
        self::assertCount(1, $user->cardsClicked);
        self::assertInstanceOf(Card::class, $user->cardsClicked[0]);
    }

    public function testFromArrayCreatesUserWithMinimalData(): void
    {
        // Setup
        $data = ['external_id' => 'user-123'];

        // Execution
        $user = User::fromArray($data);

        // Assertion
        self::assertSame('user-123', $user->externalId);
        self::assertNull($user->createdAt);
        self::assertNull($user->brazeId);
        self::assertNull($user->userAliases);
        self::assertNull($user->customEvents);
        self::assertNull($user->purchases);
        self::assertNull($user->devices);
    }

    public function testFromArrayHandlesUnknownGenderGracefully(): void
    {
        // Setup
        $data = ['external_id' => 'user-123', 'gender' => 'unknown_value'];

        // Execution
        $user = User::fromArray($data);

        // Assertion
        self::assertNull($user->gender);
    }

    public function testToArrayReturnsApiCompatibleFormat(): void
    {
        // Setup
        $createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s.v e', '2020-07-10 15:00:00.000 UTC');
        $dob = DateTimeImmutable::createFromFormat('Y-m-d', '1980-12-21');
        self::assertInstanceOf(DateTimeImmutable::class, $createdAt);
        self::assertInstanceOf(DateTimeImmutable::class, $dob);

        $user = new User(
            createdAt: $createdAt,
            externalId: 'A8i3mkd99',
            brazeId: '5fbd99bac125ca40511f2cb1',
            userAliases: [new UserAlias('user_123', 'amplitude_id')],
            firstName: 'Jane',
            lastName: 'Doe',
            dob: $dob,
            gender: Gender::Female,
            cardsClicked: [new Card(name: 'Loyalty Promo')],
        );

        // Execution
        $result = $user->toArray();

        // Assertion
        self::assertSame('2020-07-10 15:00:00.000 UTC', $result['created_at']);
        self::assertSame('A8i3mkd99', $result['external_id']);
        self::assertSame('5fbd99bac125ca40511f2cb1', $result['braze_id']);
        self::assertSame([['alias_name' => 'user_123', 'alias_label' => 'amplitude_id']], $result['user_aliases']);
        self::assertSame('Jane', $result['first_name']);
        self::assertSame('Doe', $result['last_name']);
        self::assertSame('1980-12-21', $result['dob']);
        self::assertSame('F', $result['gender']);
        self::assertSame([['name' => 'Loyalty Promo']], $result['cards_clicked']);
    }

    public function testToArrayFiltersNullValues(): void
    {
        // Setup
        $user = new User(externalId: 'user-123');

        // Execution
        $result = $user->toArray();

        // Assertion
        self::assertSame(['external_id' => 'user-123'], $result);
    }

    /**
     * @return iterable<string, array{data: array<string, mixed>, expectedMessage: string}>
     */
    public static function provideTestFromArrayThrowsExceptionForInvalidDateData(): iterable
    {
        yield 'invalid created_at' => [
            'data' => ['external_id' => 'user-123', 'created_at' => 'invalid-date'],
            'expectedMessage' => 'Invalid date format for created_at',
        ];

        yield 'invalid dob' => [
            'data' => ['external_id' => 'user-123', 'dob' => 'invalid-date'],
            'expectedMessage' => 'Invalid date format for dob',
        ];

        yield 'invalid push_opted_in_at' => [
            'data' => ['external_id' => 'user-123', 'push_opted_in_at' => 'invalid-date'],
            'expectedMessage' => 'Invalid date format for push_opted_in_at',
        ];
    }

    /**
     * @dataProvider provideTestFromArrayThrowsExceptionForInvalidDateData
     *
     * @param array<string, mixed> $data
     */
    public function testFromArrayThrowsExceptionForInvalidDate(array $data, string $expectedMessage): void
    {
        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedMessage);

        // Execution
        User::fromArray($data);
    }

    /**
     * @return array<string, mixed>
     */
    private function getFullApiResponseData(): array
    {
        return [
            'created_at' => '2020-07-10 15:00:00.000 UTC',
            'external_id' => 'A8i3mkd99',
            'user_aliases' => [['alias_name' => 'user_123', 'alias_label' => 'amplitude_id']],
            'braze_id' => '5fbd99bac125ca40511f2cb1',
            'random_bucket' => 2365,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => '[email protected]',
            'dob' => '1980-12-21',
            'home_city' => 'Chicago',
            'country' => 'US',
            'phone' => '+442071838750',
            'language' => 'en',
            'time_zone' => 'Eastern Time (US & Canada)',
            'last_coordinates' => [41.84157636433568, -87.83520818508256],
            'gender' => 'F',
            'total_revenue' => 65.0,
            'attributed_campaign' => 'braze_test_campaign_072219',
            'attributed_source' => 'braze_test_source_072219',
            'attributed_adgroup' => 'braze_test_adgroup_072219',
            'attributed_ad' => 'braze_test_ad_072219',
            'push_subscribe' => 'opted_in',
            'push_opted_in_at' => '2020-01-26T22:45:53.953Z',
            'email_subscribe' => 'subscribed',
            'custom_attributes' => [
                'loyaltyId' => '37c98b9d-9a7f-4b2f-a125-d873c5152856',
                'loyaltyPoints' => '321',
                'loyaltyPointsNumber' => 107,
            ],
            'custom_events' => [[
                'name' => 'Loyalty Acknowledgement',
                'first' => '2021-06-28T17:02:43.032Z',
                'last' => '2021-06-28T17:02:43.032Z',
                'count' => 1,
            ]],
            'purchases' => [[
                'name' => 'item_40834',
                'first' => '2021-09-05T03:45:50.540Z',
                'last' => '2022-06-03T17:30:41.201Z',
                'count' => 10,
            ]],
            'devices' => [[
                'model' => 'Pixel XL',
                'os' => 'Android (Q)',
                'carrier' => null,
                'device_id' => '312ef2c1-83db-4789-967-554545a1bf7a',
                'ad_tracking_enabled' => true,
            ]],
            'push_tokens' => [[
                'app' => 'MovieCanon',
                'platform' => 'Android',
                'token' => '12345abcd',
                'device_id' => '312ef2c1-83db-4789-967-554545a1bf7a',
                'notifications_enabled' => true,
            ]],
            'apps' => [[
                'name' => 'MovieCannon',
                'platform' => 'Android',
                'version' => '3.29.0',
                'sessions' => 1129,
                'first_used' => '2020-02-02T19:56:19.142Z',
                'last_used' => '2021-11-11T00:25:19.201Z',
            ]],
            'campaigns_received' => [[
                'name' => 'Email Unsubscribe',
                'api_campaign_id' => 'd72fdc84-ddda-44f1-a0d5-0e79f47ef942',
                'last_received' => '2022-06-02T03:07:38.105Z',
                'engaged' => ['opened_email' => true],
                'converted' => true,
                'in_control' => false,
                'variation_name' => 'Variant 1',
                'variation_api_id' => '1bddc73a-a134-4784-9134-5b5574a9e0b8',
            ]],
            'canvases_received' => [[
                'name' => 'Non Global Holdout Group 4/21/21',
                'api_canvas_id' => '46972a9d-dc81-473f-aa03-e3473b4ed781',
                'last_received_message' => '2021-07-07T20:46:24.136Z',
                'last_entered' => '2021-07-07T20:45:24.000+00:00',
                'variation_name' => 'Variant 1',
                'in_control' => false,
                'last_exited' => '2021-07-07T20:46:24.136Z',
                'steps_received' => [[
                    'name' => 'Step',
                    'api_canvas_step_id' => '43d1a349-c3c8-4be1-9fbe-ce708e4d1c39',
                    'last_received' => '2021-07-07T20:46:24.136Z',
                ]],
            ]],
            'cards_clicked' => [['name' => 'Loyalty Promo']],
        ];
    }
}

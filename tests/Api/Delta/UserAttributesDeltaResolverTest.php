<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Delta;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Delta\AttributeEncoder;
use Lingoda\BrazeBundle\Api\Delta\IdentifierResolver;
use Lingoda\BrazeBundle\Api\Delta\StorageInterface;
use Lingoda\BrazeBundle\Api\Delta\UserAttributesDeltaResolver;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Facebook;
use Lingoda\BrazeBundle\Api\Object\Location;
use Lingoda\BrazeBundle\Api\Object\Twitter;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Tests\Api\Mock\Object\InheritedUserAttributes;
use PHPUnit\Framework\TestCase;

final class UserAttributesDeltaResolverTest extends TestCase
{
    /**
     * @dataProvider deltaData
     *
     * @param array<string, mixed> $storedData
     * @param array<string, mixed> $expectedOptions
     */
    public function testDelta(UserAttributes $userAttributes, array $storedData, array $expectedOptions): void
    {
        $attributeEncoder = new AttributeEncoder();
        $storeMock = $this->createMock(StorageInterface::class);
        $storeMock
            ->method('read')
            ->willReturn($attributeEncoder->encode($storedData))
        ;

        $resolver = new UserAttributesDeltaResolver($storeMock, new IdentifierResolver());
        $resolvedAttributes = $resolver->resolveDeltaAttributes($userAttributes);

        self::assertEquals($expectedOptions, $resolvedAttributes->getOptions());
    }

    public function testDeltaReturnSameTypeAttributes(): void
    {
        $externalId = new ExternalId('id');

        $storedData = [
            'external_id' => $externalId,
            'first_name' => null,
        ];

        $expectedOptions = [
            'external_id' => $externalId,
        ];

        $userAttributes = new InheritedUserAttributes($expectedOptions);

        $attributeEncoder = new AttributeEncoder();
        $storeMock = $this->createMock(StorageInterface::class);
        $storeMock
            ->method('read')
            ->willReturn($attributeEncoder->encode($storedData))
        ;

        $resolver = new UserAttributesDeltaResolver($storeMock, new IdentifierResolver());
        $resolvedAttributes = $resolver->resolveDeltaAttributes($userAttributes);

        self::assertEquals($expectedOptions, $resolvedAttributes->getOptions());
        self::assertInstanceOf(InheritedUserAttributes::class, $resolvedAttributes);
    }

    /**
     * @return iterable<string, array{UserAttributes, array<string, mixed>, array<string, mixed>}>
     */
    public function deltaData(): iterable
    {
        $externalId = new ExternalId('id');

        yield 'no-stored-data-twitter' => [
            new UserAttributes([
                'external_id' => $externalId,
                'twitter' => new Twitter(['screen_name' => 'John']),
            ]),
            [
                'external_id' => $externalId,
                'first_name' => null,
            ],
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['screen_name' => 'John']),
            ],
        ];

        yield 'twitter-deleted' => [
            new UserAttributes([
                'external_id' => $externalId,
                'twitter' => new Twitter(['screen_name' => 'John']),
            ]),
            [
                'external_id' => $externalId,
                'twitter' => null,
            ],
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['screen_name' => 'John']),
            ],
        ];

        yield 'stored-data-twitter-id-but-updating-screen-name' => [
            new UserAttributes([
                'external_id' => $externalId,
                'twitter' => new Twitter(['screen_name' => 'John']),
            ]),
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['id' => 1]),
            ],
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['screen_name' => 'John']),
            ],
        ];

        yield 'stored-data-twitter-id' => [
            new UserAttributes([
                'external_id' => $externalId,
                'twitter' => new Twitter(['id' => 2, 'screen_name' => 'John']),
            ]),
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['id' => 1]),
            ],
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['id' => 2, 'screen_name' => 'John']),
            ],
        ];

        yield 'stored-data-twitter-id-and-screen-name' => [
            new UserAttributes([
                'external_id' => $externalId,
                'twitter' => new Twitter(['id' => 2, 'screen_name' => 'John']),
            ]),
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['id' => 1]),
            ],
            [
                'external_id' => $externalId,
                'twitter' => new Twitter(['id' => 2, 'screen_name' => 'John']),
            ],
        ];

        yield 'no-stored-data-facebook' => [
            new UserAttributes([
                'external_id' => $externalId,
                'facebook' => new Facebook(['id' => 'facebook-id', 'likes' => ['a'], 'num_friends' => 12]),
            ]),
            [
                'external_id' => $externalId,
                'first_name' => null,
            ],
            [
                'external_id' => $externalId,
                'facebook' => new Facebook(['id' => 'facebook-id', 'likes' => ['a'], 'num_friends' => 12]),
            ],
        ];

        yield 'no-stored-data-facebook-2' => [
            new UserAttributes([
                'external_id' => $externalId,
                'facebook' => new Facebook(['id' => 'facebook-id', 'likes' => ['a'], 'num_friends' => 12]),
            ]),
            [
                'external_id' => $externalId,
                'facebook' => null,
            ],
            [
                'external_id' => $externalId,
                'facebook' => new Facebook(['id' => 'facebook-id', 'likes' => ['a'], 'num_friends' => 12]),
            ],
        ];

        yield 'no-stored-data' => [
            new UserAttributes(['external_id' => $externalId]),
            [],
            [
                'external_id' => $externalId,
            ],
        ];

        yield 'stored-data' => [
            new UserAttributes([
                'external_id' => $externalId,
                'first_name' => 'John',
            ]),
            [
                'first_name' => 'John',
            ],
            [
                'external_id' => $externalId,
            ],
        ];

        yield 'stored-data-has-different-value' => [
            new UserAttributes([
                'external_id' => $externalId,
                'first_name' => 'Jim',
            ]),
            [
                'first_name' => 'John',
            ],
            [
                'external_id' => $externalId,
                'first_name' => 'Jim',
            ],
        ];

        yield 'stored-data-is-null' => [
            new UserAttributes([
                'external_id' => $externalId,
                'first_name' => null,
            ]),
            [
                'first_name' => null,
            ],
            [
                'external_id' => $externalId,
            ],
        ];

        yield 'location-same' => [
            new UserAttributes([
                'external_id' => $externalId,
                'current_location' => new Location(10.10, 12.13),
            ]),
            [
                'current_location' => new Location(10.10, 12.13),
            ],
            [
                'external_id' => $externalId,
            ],
        ];

        yield 'location-different' => [
            new UserAttributes([
                'external_id' => $externalId,
                'current_location' => new Location(10.10, 12.11),
            ]),
            [
                'current_location' => new Location(10.10, 12.13),
            ],
            [
                'external_id' => $externalId,
                'current_location' => new Location(10.10, 12.11),
            ],
        ];

        yield 'with-different-fields' => [
            new UserAttributes([
                'external_id' => $externalId,
                'email' => 'test@lingoda.com',
            ]),
            [
                'external_id' => $externalId,
                'first_name' => 'John',
            ],
            [
                'external_id' => $externalId,
                'email' => 'test@lingoda.com',
            ],
        ];

        yield 'with-same-fields-and-value' => [
            new UserAttributes([
                'external_id' => $externalId,
                'email' => 'test@lingoda.com',
            ]),
            [
                'external_id' => $externalId,
                'email' => 'test@lingoda.com',
            ],
            [
                'external_id' => $externalId,
            ],
        ];

        yield 'with-same-fields-but-remove' => [
            new UserAttributes([
                'external_id' => $externalId,
                'email' => null,
            ]),
            [
                'external_id' => $externalId,
                'email' => 'test@lingoda.com',
            ],
            [
                'external_id' => $externalId,
                'email' => null,
            ],
        ];

        yield 'with-same-fields-but-remove2' => [
            new UserAttributes([
                'external_id' => $externalId,
                'email' => null,
            ]),
            [
                'external_id' => $externalId,
                'email' => null,
            ],
            [
                'external_id' => $externalId,
            ],
        ];

        yield 'with-boolean' => [
            new UserAttributes([
                'external_id' => $externalId,
                'email_open_tracking_disabled' => true,
            ]),
            [
                'external_id' => $externalId,
            ],
            [
                'external_id' => $externalId,
                'email_open_tracking_disabled' => true,
            ],
        ];

        $now = CarbonImmutable::now();
        yield 'with-same-date' => [
            new UserAttributes([
                'external_id' => $externalId,
                'date_of_first_session' => $now,
            ]),
            [
                'external_id' => $externalId,
                'date_of_first_session' => $now,
            ],
            [
                'external_id' => $externalId,
            ],
        ];

        $newDate = new CarbonImmutable('+1 day');
        yield 'with-diff-date' => [
            new UserAttributes([
                'external_id' => $externalId,
                'date_of_first_session' => $newDate,
            ]),
            [
                'external_id' => $externalId,
                'date_of_first_session' => CarbonImmutable::now(),
            ],
            [
                'external_id' => $externalId,
                'date_of_first_session' => $newDate,
            ],
        ];
    }
}

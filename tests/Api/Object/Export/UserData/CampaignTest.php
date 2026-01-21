<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\Export\UserData\Campaign;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\Campaign
 */
final class CampaignTest extends TestCase
{
    /**
     * @return iterable<string, array{
     *     input: array<string, mixed>,
     *     expectedOptionalFields: array<string, mixed>
     * }>
     */
    public static function provideTestFromArrayAndToArrayData(): iterable
    {
        yield 'full API response' => [
            'input' => [
                'name' => 'Email Unsubscribe',
                'api_campaign_id' => 'd72fdc84-ddda-44f1-a0d5-0e79f47ef942',
                'last_received' => '2022-06-02T03:07:38.105Z',
                'engaged' => ['opened_email' => true],
                'converted' => true,
                'in_control' => false,
                'variation_name' => 'Variant 1',
                'variation_api_id' => '1bddc73a-a134-4784-9134-5b5574a9e0b8',
            ],
            'expectedOptionalFields' => [
                'variationName' => 'Variant 1',
                'variationApiId' => '1bddc73a-a134-4784-9134-5b5574a9e0b8',
                'multipleConverted' => null,
            ],
        ];

        yield 'required fields only' => [
            'input' => [
                'name' => 'Email Unsubscribe',
                'api_campaign_id' => 'd72fdc84-ddda-44f1-a0d5-0e79f47ef942',
                'last_received' => '2022-06-02T03:07:38.105Z',
                'engaged' => [],
                'converted' => false,
                'in_control' => true,
            ],
            'expectedOptionalFields' => [
                'variationName' => null,
                'variationApiId' => null,
                'multipleConverted' => null,
            ],
        ];
    }

    /**
     * @dataProvider provideTestFromArrayAndToArrayData
     *
     * @param array<string, mixed> $input
     * @param array<string, mixed> $expectedOptionalFields
     */
    public function testFromArrayAndToArray(array $input, array $expectedOptionalFields): void
    {
        // Execution
        $campaign = Campaign::fromArray($input);
        $result = $campaign->toArray();

        // Assertion
        self::assertSame($input['name'], $campaign->name);
        self::assertSame($input['api_campaign_id'], $campaign->apiCampaignId);
        self::assertSame('2022-06-02', $campaign->lastReceived->format('Y-m-d'));
        self::assertSame($input['converted'], $campaign->converted);
        self::assertSame($input['in_control'], $campaign->inControl);
        self::assertSame($expectedOptionalFields['variationName'], $campaign->variationName);
        self::assertSame($expectedOptionalFields['variationApiId'], $campaign->variationApiId);
        self::assertSame($expectedOptionalFields['multipleConverted'], $campaign->multipleConverted);
        self::assertSame('2022-06-02T03:07:38.105+00:00', $result['last_received']);
    }

    public function testFromArrayThrowsExceptionForInvalidLastReceivedDate(): void
    {
        // Setup
        $data = [
            'name' => 'Email Unsubscribe',
            'api_campaign_id' => 'd72fdc84-ddda-44f1-a0d5-0e79f47ef942',
            'last_received' => 'invalid-date',
            'engaged' => [],
            'converted' => true,
            'in_control' => false,
        ];

        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format for last_received');

        // Execution
        Campaign::fromArray($data);
    }
}

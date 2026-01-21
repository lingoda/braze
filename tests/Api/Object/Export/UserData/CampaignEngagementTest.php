<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\UserData\CampaignEngagement;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\CampaignEngagement
 */
final class CampaignEngagementTest extends TestCase
{
    /**
     * @return iterable<string, array{
     *     input: array<string, bool>,
     *     expectedProperties: array<string, bool|null>,
     *     expectedOutput: array<string, bool>
     * }>
     */
    public static function provideTestFromArrayAndToArrayData(): iterable
    {
        yield 'email opened only' => [
            'input' => ['opened_email' => true],
            'expectedProperties' => [
                'openedEmail' => true,
                'openedPush' => null,
                'clickedEmail' => null,
                'clickedTriggeredInAppMessage' => null,
            ],
            'expectedOutput' => ['opened_email' => true],
        ];

        yield 'all engagements set' => [
            'input' => [
                'opened_email' => true,
                'opened_push' => false,
                'clicked_email' => true,
                'clicked_triggered_in_app_message' => false,
            ],
            'expectedProperties' => [
                'openedEmail' => true,
                'openedPush' => false,
                'clickedEmail' => true,
                'clickedTriggeredInAppMessage' => false,
            ],
            'expectedOutput' => [
                'opened_email' => true,
                'opened_push' => false,
                'clicked_email' => true,
                'clicked_triggered_in_app_message' => false,
            ],
        ];

        yield 'empty engagement' => [
            'input' => [],
            'expectedProperties' => [
                'openedEmail' => null,
                'openedPush' => null,
                'clickedEmail' => null,
                'clickedTriggeredInAppMessage' => null,
            ],
            'expectedOutput' => [],
        ];
    }

    /**
     * @dataProvider provideTestFromArrayAndToArrayData
     *
     * @param array<string, bool> $input
     * @param array<string, bool|null> $expectedProperties
     * @param array<string, bool> $expectedOutput
     */
    public function testFromArrayAndToArray(
        array $input,
        array $expectedProperties,
        array $expectedOutput,
    ): void {
        // Execution
        $engagement = CampaignEngagement::fromArray($input);
        $result = $engagement->toArray();

        // Assertion
        self::assertSame($expectedProperties['openedEmail'], $engagement->openedEmail);
        self::assertSame($expectedProperties['openedPush'], $engagement->openedPush);
        self::assertSame($expectedProperties['clickedEmail'], $engagement->clickedEmail);
        self::assertSame($expectedProperties['clickedTriggeredInAppMessage'], $engagement->clickedTriggeredInAppMessage);
        self::assertSame($expectedOutput, $result);
    }
}

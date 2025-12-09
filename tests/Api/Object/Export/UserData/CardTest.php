<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Tests\Api\Object\Export\UserData;

use Lingoda\BrazeBundle\Api\Object\Export\UserData\Card;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lingoda\BrazeBundle\Api\Object\Export\UserData\Card
 */
final class CardTest extends TestCase
{
    public function testFromArrayAndToArrayRoundTrip(): void
    {
        // Setup
        $data = ['name' => 'Loyalty Promo'];

        // Execution
        $card = Card::fromArray($data);
        $result = $card->toArray();

        // Assertion
        self::assertSame('Loyalty Promo', $card->name);
        self::assertSame($data, $result);
    }
}

<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Object;

use Lingoda\BrazeBundle\Api\Object\Location;
use Lingoda\BrazeBundle\Tests\Api\SerializationTestCase;

final class LocationSerializationTest extends SerializationTestCase
{
    public function testSerialization(): void
    {
        self::assertSame('{"latitude":12,"longitude":46}', $this->json(new Location(12, 46)));
    }
}

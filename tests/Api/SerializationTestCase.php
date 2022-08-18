<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api;

use Lingoda\BrazeBundle\Api\BrazeSerializer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

abstract class SerializationTestCase extends KernelTestCase
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $serializer = self::$container->get('test.serializer');
        self::assertInstanceOf(SerializerInterface::class, $serializer);

        $this->serializer = $serializer;
    }

    /**
     * @param mixed $data
     */
    protected function json($data): string
    {
        return $this->serializer->serialize($data, 'json', BrazeSerializer::DEFAULT_API_CONTEXT);
    }
}

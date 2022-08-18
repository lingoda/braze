<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

use Carbon\CarbonInterface;
use Lingoda\BrazeBundle\Serializer\Normalizer\CarbonNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Wrapper class that sets up serializer context needed for Braze API
 */
class BrazeSerializer implements BrazeSerializerInterface
{
    public const DEFAULT_API_CONTEXT = [
        CarbonNormalizer::BRAZE_DATETIME_FORMAT => CarbonInterface::ATOM,
    ];

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function serialize(array $options): string
    {
        return $this->serializer->serialize($options, 'json', self::DEFAULT_API_CONTEXT);
    }
}

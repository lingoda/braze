<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Serializer\Normalizer;

use Carbon\CarbonInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

final class CarbonNormalizer implements ContextAwareNormalizerInterface
{
    public const BRAZE_DATETIME_FORMAT = 'braze_datetime_format';

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CarbonInterface && isset($context[self::BRAZE_DATETIME_FORMAT]);
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, ?string $format = null, array $context = []): string
    {
        if (!$object instanceof CarbonInterface) {
            throw new InvalidArgumentException(sprintf('The object must be an instance of %s class.', CarbonInterface::class));
        }

        if (!isset($context[self::BRAZE_DATETIME_FORMAT]) || !\is_string($context[self::BRAZE_DATETIME_FORMAT])) {
            throw new LogicException(sprintf('\'%s\' context option is missing or not a date time format.', self::BRAZE_DATETIME_FORMAT));
        }

        return $object->format((string) $context[self::BRAZE_DATETIME_FORMAT]);
    }
}

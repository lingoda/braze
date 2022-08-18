<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Serializer\Normalizer;

use Lingoda\BrazeBundle\Api\Object\IdentifierInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Helps external_ids, braze_ids and user_aliases normalization
 */
final class IdentifierNormalizer implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function normalize($object, ?string $format = null, array $context = [])
    {
        if (!$object instanceof IdentifierInterface) {
            throw new InvalidArgumentException(sprintf('The object must implement the %s interface', IdentifierInterface::class));
        }

        $data = $object->getValue();

        if (\is_array($data)) {
            $data = array_map(static fn ($value) => $value instanceof IdentifierInterface ? $value->getValue() : $value, $data);
        }

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof IdentifierInterface;
    }
}

<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use JsonSerializable;
use Lingoda\BrazeBundle\Api\Exception\LogicException;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This is the base class for every trackable objects like Purchase, Event and UserAttributes
 */
class TrackableObject implements JsonSerializable
{
    use OptionsTrait {
        setOptions as setResolvedOptions;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'external_id',
            'braze_id',
            'user_alias',
            '_update_existing_only',
            // If this value is omitted, Braze will create a new user profile if the external_id does not already exist.
        ]);

        // When creating alias-only users through this endpoint, you must explicitly set the _update_existing_only flag to false.
        $resolver->setNormalizer('_update_existing_only', function (Options $options, $value) {
            if (isset($options['user_alias'])) {
                $value = false;
            }

            return $value;
        });

        $resolver
            ->setAllowedTypes('external_id', ExternalId::class)
            ->setAllowedTypes('braze_id', BrazeId::class)
            ->setAllowedTypes('user_alias', UserAlias::class)
            ->setAllowedTypes('_update_existing_only', 'bool')
        ;
    }

    /**
     * @param array<string, mixed> $resolvedOptions
     */
    protected function setOptions(array $resolvedOptions): void
    {
        $countIdentifiers = \count(array_intersect(
            array_keys($resolvedOptions),
            ['braze_id', 'external_id', 'user_alias']
        ));

        if ($countIdentifiers === 0) {
            throw new LogicException('One of "external_id" or "user_alias" or "braze_id" is required');
        }

        if ($countIdentifiers > 1) {
            throw new LogicException('Too many identifiers. Use one of "external_id" or "user_alias" or "braze_id" identifier');
        }

        $this->setResolvedOptions($resolvedOptions);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->getOptions();
    }
}

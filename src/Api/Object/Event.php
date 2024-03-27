<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * EventObject
 *
 * @see https://www.braze.com/docs/api/objects_filters/event_object/
 */
class Event extends TrackableObject
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'app_id',
            'time',
            'name',
            'properties',
        ]);

        $resolver->setRequired([
            'name',
        ]);

        $resolver->setDefault('time', CarbonImmutable::now());

        $resolver
            ->setAllowedTypes('app_id', AppId::class)
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('time', CarbonInterface::class)
            ->setAllowedTypes('properties', Properties::class)
        ;

        $resolver
            ->setAllowedValues('name', Validation::createIsValidCallable(
                new NotBlank()
            ))
        ;
    }

    public function hasProperties(): bool
    {
        $properties = $this->getOptions()['properties'] ?? null;
        if ($properties instanceof Properties) {
            return !$properties->isEmpty();
        }

        return false;
    }
}

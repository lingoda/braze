<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Lingoda\BrazeBundle\Validator\Validation;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * PurchaseObject
 *
 * @see https://www.braze.com/docs/api/objects_filters/purchase_object/
 */
class Purchase extends TrackableObject
{
    private const MAX_QUANTITY = 100;
    private const MIN_QUANTITY = 1;

    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'app_id',
            'product_id', // identifier for the purchase, e.g. Product Name or Product Category
            'currency', // ISO 4217 Alphabetic Currency Code
            'price',
            'quantity',
            'time',
            'properties',
        ]);

        $resolver->setRequired([
            'app_id',
            'product_id',
            'currency',
            'price',
            'time',
        ]);

        $resolver->setDefault('time', CarbonImmutable::now());

        $resolver
            ->setAllowedTypes('app_id', AppId::class)
            ->setAllowedTypes('product_id', 'string')
            ->setAllowedTypes('currency', 'string')
            ->setAllowedTypes('price', 'float')
            ->setAllowedTypes('quantity', 'integer')
            ->setAllowedTypes('time', CarbonInterface::class)
            ->setAllowedTypes('properties', Properties::class)
        ;

        $resolver
            // defaults to 1, must be <= 100 -- currently, Braze treats a quantity _X_ as _X_ separate purchases with quantity 1)
            ->setAllowedValues('quantity', fn ($value) => $value > self::MIN_QUANTITY && $value <= self::MAX_QUANTITY)

            // @TODO add Currency support?!
            ->setAllowedValues('currency', Validation::createIsValidCallback(
                new NotBlank(),
                new Length(3)
            ))
            ->setAllowedValues('product_id', Validation::createIsValidCallback(
                new NotBlank(),
                new Length(['max' => 255])
            ))
        ;
    }
}

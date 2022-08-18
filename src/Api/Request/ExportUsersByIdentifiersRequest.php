<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Request;

use Lingoda\BrazeBundle\Api\BrazeApiLimitsInterface;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Validator\Validation;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Webmozart\Assert\Assert;

class ExportUsersByIdentifiersRequest
{
    use OptionsTrait {
        setOptions as setResolvedOptions;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'external_ids',
            'user_aliases',
            'device_id',
            'braze_id',
            'email_address',
            'phone',
            'fields_to_export',
        ]);

        $resolver
            ->setAllowedTypes('external_ids', ExternalId::class . '[]')
            ->setAllowedTypes('user_aliases', UserAlias::class . '[]')
            ->setAllowedTypes('device_id', 'string')
            ->setAllowedTypes('braze_id', BrazeId::class)
            ->setAllowedTypes('email_address', 'string')
            ->setAllowedTypes('phone', 'string')
            ->setAllowedTypes('fields_to_export', 'string[]')
        ;

        $resolver
            ->setAllowedValues('external_ids', Validation::createIsValidCallback(
                new NotBlank(),
                new Count(['min' => 0, 'max' => BrazeApiLimitsInterface::BRAZE_USER_EXPORT_LIMIT])
            ))
            ->setAllowedValues('user_aliases', Validation::createIsValidCallback(
                new NotBlank(),
                new Count(['min' => 0, 'max' => BrazeApiLimitsInterface::BRAZE_USER_EXPORT_LIMIT])
            ))
        ;
    }

    /**
     * @param array<string,mixed> $resolvedOptions
     */
    protected function setOptions(array $resolvedOptions): void
    {
        Assert::isNonEmptyMap(
            $resolvedOptions,
            'At least one of the parameters "external_ids", "user_aliases", "device_id", "braze_id", "email_address" should be defined'
        );

        $this->setResolvedOptions($resolvedOptions);
    }
}

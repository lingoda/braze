<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Request;

use Lingoda\BrazeBundle\Api\Object\SegmentId;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Lingoda\BrazeBundle\Validator\Validation;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Webmozart\Assert\Assert;

class ExportUsersBySegmentRequest
{
    use OptionsTrait {
        setOptions as setResolvedOptions;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'segment_id',
            'callback_endpoint',
            'fields_to_export',
        ]);

        $resolver
            ->setAllowedTypes('segment_id', SegmentId::class)
            ->setAllowedTypes('callback_endpoint', 'string')
            ->setAllowedTypes('fields_to_export', 'string[]')
        ;
    }

    /**
     * @param array<string,mixed> $resolvedOptions
     */
    protected function setOptions(array $resolvedOptions): void
    {
        Assert::isNonEmptyMap(
            $resolvedOptions,
            'The parameter "segment_id" should be defined'
        );

        $this->setResolvedOptions($resolvedOptions);
    }
}

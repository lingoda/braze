<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use JsonSerializable;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class Facebook implements JsonSerializable
{
    use OptionsTrait {
        OptionsTrait::setOptions as setResolvedOptions;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'id',
            'likes',
            'num_friends',
        ]);

        $resolver->setAllowedTypes('id', 'string');
        $resolver->setAllowedTypes('likes', 'string[]');
        $resolver->setAllowedTypes('num_friends', 'integer');
    }

    /**
     * @param array<string,mixed> $resolvedOptions
     */
    protected function setOptions(array $resolvedOptions): void
    {
        Assert::notEmpty(
            $resolvedOptions,
            'At least one of the properties needs to be defined. (id, likes, num_friends)'
        );

        $this->setResolvedOptions($resolvedOptions);
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->getOptions();
    }
}

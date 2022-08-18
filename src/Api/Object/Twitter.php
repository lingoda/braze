<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use JsonSerializable;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class Twitter implements JsonSerializable
{
    use OptionsTrait {
        OptionsTrait::setOptions as setResolvedOptions;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'id',
            'screen_name',
            'followers_count',
            'friends_count',
            'statuses_count',
        ]);

        $resolver->setAllowedTypes('id', 'integer');
        $resolver->setAllowedTypes('screen_name', 'string');
        $resolver->setAllowedTypes('followers_count', 'integer');
        $resolver->setAllowedTypes('friends_count', 'integer');
        $resolver->setAllowedTypes('statuses_count', 'integer');
    }

    /**
     * @param array<string,mixed> $resolvedOptions
     */
    protected function setOptions(array $resolvedOptions): void
    {
        Assert::notEmpty(
            $resolvedOptions,
            'At least on of the properties needs to be defined. (id, screen_name, followers_count, friends_count, statuses_count)'
        );

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

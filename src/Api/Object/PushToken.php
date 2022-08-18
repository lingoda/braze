<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use JsonSerializable;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PushToken implements JsonSerializable
{
    use OptionsTrait;

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['app_id', 'token', 'device_id']);

        $resolver->setRequired('app_id');
        $resolver->setRequired('token');

        $resolver->setAllowedTypes('app_id', 'string');
        $resolver->setAllowedTypes('token', 'string');
        $resolver->setAllowedTypes('device_id', 'string');
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->getOptions();
    }
}

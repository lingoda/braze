<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

interface BrazeSerializerInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function serialize(array $options): string;
}

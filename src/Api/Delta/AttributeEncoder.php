<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Delta;

use Lingoda\BrazeBundle\Api\Object\Facebook;
use Lingoda\BrazeBundle\Api\Object\Twitter;

class AttributeEncoder
{
    /**
     * @param array<string, mixed> $options $options
     *
     * @return array<string, mixed>
     */
    public function encode(array $options): array
    {
        return array_map(
            function ($v) {
                if ($v instanceof Facebook || $v instanceof Twitter) {
                    return $this->encode($v->getOptions());
                }

                if (\is_array($v)) {
                    return $this->encode($v);
                }

                return hash('sha256', (string) $v);
            },
            $options
        );
    }
}

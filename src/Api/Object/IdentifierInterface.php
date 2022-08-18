<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

interface IdentifierInterface
{
    /**
     * @return string|array{alias_name: string, alias_label: string, external_id?: ExternalId}
     */
    public function getValue();
}

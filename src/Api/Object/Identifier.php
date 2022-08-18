<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use Webmozart\Assert\Assert;

abstract class Identifier implements IdentifierInterface
{
    private string $id;

    public function __construct(string $id)
    {
        Assert::notEmpty($id);
        $this->id = $id;
    }

    public function getValue(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}

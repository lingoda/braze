<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use Webmozart\Assert\Assert;

final class AppId extends Identifier
{
    public function __construct(string $id)
    {
        Assert::uuid($id);
        parent::__construct($id);
    }
}

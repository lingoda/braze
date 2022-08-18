<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

use Lingoda\BrazeBundle\Api\Endpoint\Export;
use Lingoda\BrazeBundle\Api\Endpoint\Messaging;
use Lingoda\BrazeBundle\Api\Endpoint\Users;

interface BrazeApiInterface
{
    public function users(): Users;

    public function messaging(): Messaging;

    public function export(): Export;
}

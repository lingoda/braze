<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Endpoint;

use Lingoda\BrazeBundle\Api\BrazeClientInterface;

abstract class Endpoint
{
    protected BrazeClientInterface $client;

    public function __construct(BrazeClientInterface $client)
    {
        $this->client = $client;
    }
}

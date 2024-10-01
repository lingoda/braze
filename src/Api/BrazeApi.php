<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api;

use Lingoda\BrazeBundle\Api\Endpoint\Export;
use Lingoda\BrazeBundle\Api\Endpoint\Messaging;
use Lingoda\BrazeBundle\Api\Endpoint\Subscription;
use Lingoda\BrazeBundle\Api\Endpoint\SubscriptionGroups;
use Lingoda\BrazeBundle\Api\Endpoint\Users;

final class BrazeApi implements BrazeApiInterface
{
    public function __construct(
        private readonly Users $users,
        private readonly Messaging $messaging,
        private readonly Export $export,
        private readonly SubscriptionGroups $subscriptionGroups,
    ) {
    }

    public function users(): Users
    {
        return $this->users;
    }

    public function messaging(): Messaging
    {
        return $this->messaging;
    }

    public function export(): Export
    {
        return $this->export;
    }

    public function subscriptionGroups(): SubscriptionGroups
    {
        return $this->subscriptionGroups;
    }
}

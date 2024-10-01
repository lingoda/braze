<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Endpoint;

use Lingoda\BrazeBundle\Api\BrazeClientInterface;
use Lingoda\BrazeBundle\Api\Constants\SubscriptionType;
use Lingoda\BrazeBundle\Api\Endpoint\Endpoint;
use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\SubscriptionGroupId;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;

/**
 * Subscription Groups endpoint
 *
 * @see https://www.braze.com/docs/api/endpoints/subscription_groups
 */
class SubscriptionGroups extends Endpoint
{
	private const BASE_ENDPOINT = 'subscriptions';

	public function __construct(BrazeClientInterface $client)
	{
		parent::__construct($client);
	}

	/**
	 * Use this endpoint to update user subscription status for a specific subscription group
	 *
	 * @see https://www.braze.com/docs/api/endpoints/subscription_groups/post_update_user_subscription_group_status
	 *
	 * @throws HttpException
	 */
	public function setStatus(
		SubscriptionGroupId $subscriptionGroupId,
		SubscriptionType $state,
		ExternalId $externalId,
		string $phone
	): ApiResponseInterface
	{
		return $this->client->post(
			self::BASE_ENDPOINT . '/status/set',
			[
				'attributes' => [
					'external_id' => $externalId,
					'phone' => $phone,
					'subscription_group_id' => $subscriptionGroupId,
					'subscription_state' => $state
				],
			]
		);
	}
}

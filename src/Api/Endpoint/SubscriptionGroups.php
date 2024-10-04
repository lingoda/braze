<?php

declare(strict_types=1);

namespace Lingoda\BrazeBundle\Api\Endpoint;

use Lingoda\BrazeBundle\Api\Constants\SubscriptionType;
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
	private const BASE_ENDPOINT = 'subscription';

	/**
	 * Use this endpoint to update user subscription status for a specific subscription group
	 *
	 * @see https://www.braze.com/docs/api/endpoints/subscription_groups/post_update_user_subscription_group_status
	 *
	 * @param SubscriptionType::* $state
	 *
	 * @throws HttpException
	 */
	public function setStatus(
		SubscriptionGroupId $subscriptionGroupId,
		string $state,
		ExternalId $externalId,
		string $phone
	): ApiResponseInterface
	{
		return $this->client->post(
			self::BASE_ENDPOINT . '/status/set',
			[
				'external_id' => $externalId,
				'phone' => $phone,
				'subscription_group_id' => $subscriptionGroupId,
				'subscription_state' => $state
			]
		);
	}
}

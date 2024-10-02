# Subscription groups

Subscription groups are used to subscribe user to some marketing channels, for example sms or whatsapp.
Subscription groups ids could be seen in Braze, please ask CRM team for an ID of the group that you need.

## update user subscription to some group

```php

use App\Braze\User\Object\UserExternalId;
use App\Braze\User\Object\UserSubscriptionGroupId;
use Lingoda\BrazeBundle\Api\BrazeApiInterface;
use Lingoda\BrazeBundle\Api\Constants\SubscriptionType;



/**
 * @throws HttpException
 */
public function updatePhoneSubscriptions(User $user, SubscriptionType $status): void
{

	try {
		$this->brazeApi->subscriptions()->setStatus(
			new UserSubscriptionGroupId('subscription-group-id'),
			$status,
			UserExternalId::create($user),
			$user->getPhoneNumber()
		);
	} catch (BrazeApiException $e) {
		// handle exception
	}
}
```

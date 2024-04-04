<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

use Lingoda\BrazeBundle\Api\OriginalResponseAwareTrait;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Response for sending campaign messages via API-Triggered delivery.
 *
 * @see https://www.braze.com/docs/api/endpoints/messaging/send_messages/post_send_triggered_campaigns/#request-parameters
 */
class ApiMessagingCampaignSendResponse extends ApiResponse implements OriginalResponseAwareInterface
{
    use OriginalResponseAwareTrait;

    #[SerializedName("dispatch_id")]
    private string $dispatchId;

    public function getDispatchId(): string
    {
        return $this->dispatchId;
    }

    public function setDispatchId(string $dispatchId): void
    {
        $this->dispatchId = $dispatchId;
    }
}

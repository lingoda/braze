<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Endpoint;

use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Lingoda\BrazeBundle\Api\Object\CampaignId;
use Lingoda\BrazeBundle\Api\Object\Recipient;
use Lingoda\BrazeBundle\Api\Object\SendId;
use Lingoda\BrazeBundle\Api\Object\TriggerProperties;
use Lingoda\BrazeBundle\Api\Response\ApiMessagingCampaignSendResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

/**
 * Messaging Endpoints
 *
 * @see https://www.braze.com/docs/api/endpoints/messaging/#nav_top_endpoints_messaging_sendmessages
 */
class Messaging extends Endpoint
{
    private const BASE_ENDPOINT = 'campaigns/trigger';

    /**
     * Use this endpoint to send campaign messages via API-Triggered Delivery.
     *
     * @see https://www.braze.com/docs/api/endpoints/messaging/send_messages/post_send_triggered_campaigns/
     *
     * @param array{
     *      campaign_id?: CampaignId,
     *      send_id?: SendId,
     *      trigger_properties?: TriggerProperties,
     *      broadcast?: boolean,
     *      audience?: string[],
     *      recipients?: Recipient[],
     * } $options
     *
     * @throws HttpException
     */
    public function sendCampaignMessages(array $options = []): ApiMessagingCampaignSendResponse
    {
        $resolver = (new OptionsResolver())
            ->setDefined([
                'campaign_id',
                'send_id',
                'trigger_properties',
                'broadcast',
                'audience',
                'recipients',
            ])
            ->setAllowedTypes('campaign_id', [CampaignId::class])
            ->setAllowedTypes('send_id', [SendId::class])
            ->setAllowedTypes('trigger_properties', [TriggerProperties::class])
            ->setAllowedTypes('broadcast', ['boolean'])
            ->setAllowedTypes('audience', ['array'])
            ->setAllowedTypes('recipients', [Recipient::class . '[]'])
        ;

        $options = $resolver->resolve($options);

        $response = $this->client->post(self::BASE_ENDPOINT . '/send', $options, ApiMessagingCampaignSendResponse::class);
        Assert::isInstanceOf($response, ApiMessagingCampaignSendResponse::class);

        return $response;
    }
}

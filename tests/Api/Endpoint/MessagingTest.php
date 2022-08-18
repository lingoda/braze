<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Tests\Api\Endpoint;

use Lingoda\BrazeBundle\Api\Object\CampaignId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Recipient;
use Lingoda\BrazeBundle\Api\Object\SendId;
use Lingoda\BrazeBundle\Api\Object\TriggerProperties;
use Lingoda\BrazeBundle\Tests\Api\BrazeApiTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;

final class MessagingTest extends BrazeApiTestCase
{
    private TriggerProperties $properties;
    /** @var array<string, mixed> */
    private array $audience;
    private Recipient $recipient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockHttpClient();

        $this->properties = new TriggerProperties(['key' => 'value']);
        $this->audience = [
            'AND' => [
                'Connected Audience Filter',
                [
                    'OR' => [
                        'Connected Audience Filter',
                        'Connected Audience Filter',
                    ],
                ],
                'Connected Audience Filter',
            ],
        ];

        $recipient = new Recipient([
            'external_user_id' => new ExternalId('user_id'),
        ]);
        $this->recipient = $recipient;
    }

    public function testSendCampaignMessages(): void
    {
        $this->setMockClientResponses([
            'campaigns/trigger/send' => new MockResponse((string) json_encode([
                'dispatch_id' => '74a3d875c1b22f49f3fac4e9eda7fd89',
                'message' => 'success',
            ], \JSON_THROW_ON_ERROR)),
        ]);

        $response = $this->brazeApi->messaging()->sendCampaignMessages([
            'send_id' => new SendId('1234'),
            'trigger_properties' => $this->properties,
            'broadcast' => true,
            'audience' => $this->audience,
            'recipients' => [$this->recipient],
            'campaign_id' => new CampaignId('12345'),
        ]);

        self::assertTrue($response->isSuccess());
        self::assertEquals('74a3d875c1b22f49f3fac4e9eda7fd89', $response->getDispatchId());
    }
}

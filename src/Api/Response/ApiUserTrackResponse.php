<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Response;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Users Track Successful Message
 *
 * @see https://www.braze.com/docs/api/endpoints/user_data/post_user_track/#responses
 */
class ApiUserTrackResponse extends ApiResponse
{
    #[SerializedName('attributes_processed')]
    private int $attributesProcessed = 0;

    #[SerializedName('events_processed')]
    private int $eventsProcessed = 0;

    #[SerializedName('purchases_processed')]
    private int $purchasesProcessed = 0;

    public function __construct(string $message = self::MESSAGE_SUCCESS, array $errors = [])
    {
        parent::__construct($message, $errors);
    }

    public function getAttributesProcessed(): int
    {
        return $this->attributesProcessed;
    }

    public function setAttributesProcessed(int $attributesProcessed): void
    {
        $this->attributesProcessed = $attributesProcessed;
    }

    public function getEventsProcessed(): int
    {
        return $this->eventsProcessed;
    }

    public function setEventsProcessed(int $eventsProcessed): void
    {
        $this->eventsProcessed = $eventsProcessed;
    }

    public function getPurchasesProcessed(): int
    {
        return $this->purchasesProcessed;
    }

    public function setPurchasesProcessed(int $purchasesProcessed): void
    {
        $this->purchasesProcessed = $purchasesProcessed;
    }
}

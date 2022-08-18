<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Helper object for creating an alias identification
 *
 * @see https://www.braze.com/docs/api/objects_filters/aliases_to_identify/
 */
class AliasToIdentify
{
    /**
     * @SerializedName("external_id")
     */
    private ExternalId $externalId;

    /**
     * @SerializedName("user_alias")
     */
    private UserAlias $userAlias;

    public function __construct(ExternalId $externalId, UserAlias $userAlias)
    {
        $this->externalId = $externalId;
        $this->userAlias = $userAlias;
    }

    public function getExternalId(): ExternalId
    {
        return $this->externalId;
    }

    public function getUserAlias(): UserAlias
    {
        return $this->userAlias;
    }
}

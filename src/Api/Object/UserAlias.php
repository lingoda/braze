<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use Webmozart\Assert\Assert;

/**
 * @see https://www.braze.com/docs/api/objects_filters/user_alias_object/
 */
class UserAlias implements IdentifierInterface
{
    private ?ExternalId $externalId;
    private string $aliasName;
    private string $aliasLabel;

    public function __construct(string $aliasName, string $aliasLabel, ?ExternalId $externalId = null)
    {
        Assert::notEmpty($aliasName);
        Assert::notEmpty($aliasLabel);

        $this->aliasName = $aliasName;
        $this->aliasLabel = $aliasLabel;
        $this->externalId = $externalId;
    }

    public function getExternalId(): ?ExternalId
    {
        return $this->externalId;
    }

    public function getAliasName(): string
    {
        return $this->aliasName;
    }

    public function getAliasLabel(): string
    {
        return $this->aliasLabel;
    }

    /**
     * @return array{alias_name: string, alias_label: string, external_id?: ExternalId}
     */
    public function getValue(): array
    {
        $data = [
            'alias_name' => $this->aliasName,
            'alias_label' => $this->aliasLabel,
        ];

        if (null !== $this->externalId) {
            $data['external_id'] = $this->externalId;
        }

        return $data;
    }

    public function __toString(): string
    {
        return $this->aliasName . ':' . $this->aliasLabel;
    }
}

<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Delta;

use Lingoda\BrazeBundle\Api\Object\IdentifierInterface;
use Lingoda\BrazeBundle\Api\Object\Property\TrackableObjectProperties;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Webmozart\Assert\Assert;

/**
 * Resolvers current UserAttributes identifier
 */
class IdentifierResolver
{
    private const SUPPORTED_OPTIONS = [
        TrackableObjectProperties::EXTERNAL_ID,
        TrackableObjectProperties::USER_ALIAS,
        TrackableObjectProperties::BRAZE_ID,
    ];

    public function resolve(UserAttributes $userAttributes): ?string
    {
        foreach (self::SUPPORTED_OPTIONS as $option) {
            if ($userAttributes->hasOption($option)) {
                return $this->toStringId($userAttributes->getOption($option));
            }
        }

        return null;
    }

    private function toStringId(IdentifierInterface $identifier): string
    {
        if ($identifier instanceof UserAlias) {
            return $identifier->getAliasName() . ':' . $identifier->getAliasLabel();
        }

        $value = $identifier->getValue();
        Assert::string($value);

        return $value;
    }
}

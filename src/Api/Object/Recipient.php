<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use JsonSerializable;
use Lingoda\BrazeBundle\Api\Exception\LogicException;
use Lingoda\BrazeBundle\Api\Object\Property\RecipientProperties;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The Recipients Object allows you to request or write information in our endpoints.
 *
 * NOTE: When making an API call with the Recipient Object,
 * if there exists a duplicated recipient targeting the same address (ie email, push),
 * the user will be deduped, meaning identical users will be removed, leaving one.
 *
 * @see https://www.braze.com/docs/api/objects_filters/recipient_object/ - Recipient Object Deduping
 */
class Recipient implements JsonSerializable
{
    use OptionsTrait;

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            RecipientProperties::USER_ALIAS,
            RecipientProperties::EXTERNAL_USER_ID,
            RecipientProperties::TRIGGER_PROPERTIES,
            RecipientProperties::CANVAS_ENTRY_PROPERTIES,
        ]);

        $resolver
            ->setAllowedTypes(RecipientProperties::USER_ALIAS, UserAlias::class)
            ->setAllowedTypes(RecipientProperties::EXTERNAL_USER_ID, ExternalId::class)
            ->setAllowedTypes(RecipientProperties::TRIGGER_PROPERTIES, TriggerProperties::class)
            ->setAllowedTypes(RecipientProperties::CANVAS_ENTRY_PROPERTIES, CanvasEntryProperties::class)
        ;
    }

    /**
     * @param array<string,mixed> $resolvedOptions
     */
    protected function setOptions(array $resolvedOptions): void
    {
        $countIdentifiers = \count(array_intersect(
            array_keys($resolvedOptions),
            [RecipientProperties::EXTERNAL_USER_ID, RecipientProperties::USER_ALIAS]
        ));

        if ($countIdentifiers === 0) {
            throw new LogicException('One of "external_user_id" or "user_alias" is required');
        }

        if ($countIdentifiers > 1) {
            throw new LogicException('Only one of "external_user_id" or "user_alias" needs to be set');
        }

        $this->options = $resolvedOptions;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->getOptions();
    }
}

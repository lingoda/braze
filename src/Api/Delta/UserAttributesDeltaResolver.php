<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Delta;

use Lingoda\BrazeBundle\Api\Object\Facebook;
use Lingoda\BrazeBundle\Api\Object\Property\TrackableObjectProperties;
use Lingoda\BrazeBundle\Api\Object\Property\UserAttributesProperties;
use Lingoda\BrazeBundle\Api\Object\Twitter;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use ReflectionClass;
use Webmozart\Assert\Assert;

class UserAttributesDeltaResolver
{
    private const NON_REMOVABLE_ATTRIBUTES = [
        TrackableObjectProperties::BRAZE_ID,
        TrackableObjectProperties::EXTERNAL_ID,
        TrackableObjectProperties::USER_ALIAS,
        TrackableObjectProperties::UPDATE_EXISTING_ONLY,
    ];

    private StorageInterface $deltaStore;
    private IdentifierResolver $identifierResolver;
    private AttributeEncoder $attributeEncoder;

    public function __construct(StorageInterface $store, IdentifierResolver $identifierResolver)
    {
        $this->deltaStore = $store;
        $this->identifierResolver = $identifierResolver;
        $this->attributeEncoder = new AttributeEncoder();
    }

    /**
     * @param UserAttributes[] $attributes
     *
     * @return UserAttributes[]
     */
    public function resolveAll(array $attributes): array
    {
        return array_map(
            fn (UserAttributes $userAttributes): UserAttributes => $this->resolveDeltaAttributes($userAttributes),
            $attributes
        );
    }

    public function resolveDeltaAttributes(UserAttributes $userAttributes): UserAttributes
    {
        $storedOptions = $this->getStoredOptions($userAttributes);
        if (!$storedOptions) {
            return $userAttributes;
        }

        $options = $userAttributes->getOptions();
        /** @var array<UserAttributesProperties::*, mixed> $delta */
        $delta = $this->resolveDelta($options, $storedOptions);

        return $userAttributes::withOptions($delta);
    }

    public function storeDeltaAttributes(UserAttributes $userAttributes): void
    {
        $id = $this->identifierResolver->resolve($userAttributes);
        Assert::notNull($id);

        $allOptions = $this->attributeEncoder->encode(($userAttributes->getOptions()));
        $storedOptions = $this->getStoredOptions($userAttributes);
        if ($storedOptions) {
            /** @var array<UserAttributesProperties::*, mixed> $allOptions */
            $allOptions = array_merge(
                $storedOptions,
                $allOptions
            );
        }

        $this->deltaStore->write($id, $allOptions);
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $storedEncodedOptions
     *
     * @return array<string, mixed>
     */
    private function resolveDelta(array $options, array $storedEncodedOptions): array
    {
        $encodedOptions = $this->attributeEncoder->encode($options);
        $filtered = $this->filter($options, $encodedOptions, $storedEncodedOptions);

        array_walk($filtered, function (&$item, $key) use ($encodedOptions, $storedEncodedOptions): void {
            if (!\array_key_exists($key, $storedEncodedOptions)) {
                return;
            }

            // embedded object with partial update
            if (!($item instanceof Facebook || $item instanceof Twitter)) {
                return;
            }

            $storedValue = $storedEncodedOptions[$key] ?? [];
            if (!\is_array($storedValue)) {
                return;
            }

            $filteredArgs = $this->filter(
                $item->getOptions(),
                (array) $encodedOptions[$key],
                $storedValue
            );

            try {
                $item = (new ReflectionClass($item))->newInstance($filteredArgs);
            } catch (\ReflectionException $e) {
                // fallback to initial value. this shouldn't be the case though
            }
        });

        return $filtered;
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $encodedOptions
     * @param array<string, mixed> $storedEncodedOptions
     *
     * @return array<string, mixed>
     */
    protected function filter(array $options, array $encodedOptions, array $storedEncodedOptions): array
    {
        return array_filter($options, function ($v, $k) use ($encodedOptions, $storedEncodedOptions): bool {
            // keep special keys
            if (\in_array($k, self::NON_REMOVABLE_ATTRIBUTES, true)) {
                return true;
            }

            // keep if not stored previously
            if (!\array_key_exists($k, $storedEncodedOptions)) {
                return true;
            }

            // embedded object needs special treatment
            if ($v instanceof Facebook || $v instanceof Twitter) {
                if (!\is_array($storedEncodedOptions[$k])) {
                    return true;
                }

                return !empty($this->resolveDelta($v->getOptions(), $storedEncodedOptions[$k]));
            }

            // single dimensional arrays
            if (\is_array($v)) {
                return !empty(array_diff($encodedOptions[$k], $storedEncodedOptions[$k]));
            }

            // value comparison
            return !hash_equals($encodedOptions[$k], $storedEncodedOptions[$k]);
        }, \ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getStoredOptions(UserAttributes $userAttributes): ?array
    {
        return ($id = $this->identifierResolver->resolve($userAttributes)) ? $this->deltaStore->read($id) : null;
    }
}

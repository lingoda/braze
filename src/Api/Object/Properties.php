<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use ArrayAccess;
use Countable;
use JsonSerializable;
use Webmozart\Assert\Assert;

/**
 * Event, Purchase object Properties Object
 *
 * @template-implements ArrayAccess<string, mixed>
 */
class Properties implements Countable, ArrayAccess, JsonSerializable
{
    /**
     * @var array<string, mixed>
     */
    private array $properties = [];

    /**
     * @param array<string, mixed> $properties
     */
    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            $this->offsetSet($property, $value);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function count(): int
    {
        return \count($this->properties);
    }

    /**
     * @param string $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->properties[$offset]) || \array_key_exists($offset, $this->properties);
    }

    /**
     * @param string $offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->properties[$offset] ?? null;
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        Assert::string($offset, 'Property names must be non-empty strings less than or equal to 255 characters');
        Assert::notEmpty($offset, 'Property names must be non-empty strings');
        Assert::maxLength($offset, 255, 'Property must be less than or equal to 255 characters');
        Assert::notStartsWith($offset, '$', 'Property names must be non-empty strings with no leading dollar signs');

        $this->properties[$offset] = $value;
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset): void
    {
        if (!$this->offsetExists($offset)) {
            return;
        }

        unset($this->properties[$offset]);
    }

    public function isEmpty(): bool
    {
        return empty($this->properties);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->properties;
    }
}

<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Request;

use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\Purchase;
use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Webmozart\Assert\Assert;

class TrackUserDataRequest
{
    use OptionsTrait {
        setOptions as setResolvedOptions;
    }

    private const BRAZE_TRACK_OBJECT_LIMIT = 75;

    /**
     * @param Event[] $events
     *
     *  @return static
     */
    public static function withEvents(array $events): self
    {
        return new static(['events' => $events]);
    }

    /**
     * @param UserAttributes[] $attributes
     *
     * @return static
     */
    public static function withAttributes(array $attributes): self
    {
        return new static(['attributes' => $attributes]);
    }

    /**
     * @param Purchase[] $purchases
     *
     * @return static
     */
    public static function withPurchases(array $purchases): self
    {
        return new static(['purchases' => $purchases]);
    }

    /**
     * @param array<string,mixed> $options
     *
     * @return static
     */
    public function withOptions(array $options): self
    {
        return new static($options);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'events',
            'attributes',
            'purchases',
        ]);

        $resolver
            ->setAllowedTypes('events', Event::class . '[]')
            ->setAllowedTypes('attributes', UserAttributes::class . '[]')
            ->setAllowedTypes('purchases', Purchase::class . '[]')
        ;

        $resolver
            ->setAllowedValues('events', Validation::createIsValidCallable(new NotBlank()))
            ->setAllowedValues('attributes', Validation::createIsValidCallable(new NotBlank()))
            ->setAllowedValues('purchases', Validation::createIsValidCallable(new NotBlank()))
        ;
    }

    /**
     * @param array<string,mixed> $resolvedOptions
     */
    protected function setOptions(array $resolvedOptions): void
    {
        Assert::isNonEmptyMap(
            $resolvedOptions,
            'At least one of the parameters "attributes", "events", "purchases" should be defined'
        );

        self::assertTrackParameterUpperLimit($resolvedOptions, 'attributes');
        self::assertTrackParameterUpperLimit($resolvedOptions, 'events');
        self::assertTrackParameterUpperLimit($resolvedOptions, 'purchases');

        $this->setResolvedOptions($resolvedOptions);
    }

    /**
     * @return UserAttributes[]
     */
    public function getAttributes(): array
    {
        return $this->getOption('attributes');
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->getOption('events');
    }

    /**
     * @return Purchase[]
     */
    public function getPurchases(): array
    {
        return $this->getOption('purchases');
    }

    public function hasEvents(): bool
    {
        return $this->hasOption('events') && !empty($this->getEvents());
    }

    public function hasAttributes(): bool
    {
        return $this->hasOption('attributes') && !empty($this->getAttributes());
    }

    public function hasPurchases(): bool
    {
        return $this->hasOption('purchases') && !empty($this->getPurchases());
    }

    /**
     * @param array<string,mixed> $options
     */
    private static function assertTrackParameterUpperLimit(array $options, string $parameterName): void
    {
        Assert::nullOrMaxCount(
            $options[$parameterName] ?? null,
            self::BRAZE_TRACK_OBJECT_LIMIT,
            sprintf('Each request can contain up to %d %s', self::BRAZE_TRACK_OBJECT_LIMIT, $parameterName)
        );
    }
}

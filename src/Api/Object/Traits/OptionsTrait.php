<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object\Traits;

use Lingoda\BrazeBundle\Api\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

trait OptionsTrait
{
    protected OptionsResolver $resolver;

    /**
     * @var array<string,mixed>
     */
    private array $options;

    /**
     * @param array<string,mixed> $options
     */
    final public function __construct(array $options, ?OptionsResolver $optionsResolver = null)
    {
        $this->resolver = $optionsResolver ?? new OptionsResolver();
        $this->configureOptions($this->resolver);

        $this->resolveAndSetOptions($options);

        // We need to call setOptions() here, even though we call resolveAndSetOptions above.
        // This is because a class that uses this Trait can override setOptions()
        // And it expects the options to be resolved already
        $resolvedOptions = $this->getOptions();
        $this->setOptions($resolvedOptions);
    }

    /**
     * Override this method to extend default configuration.
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @param array<string,mixed> $options
     */
    protected function setOptions(array $options): void
    {
        $this->resolveAndSetOptions($options);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    protected function addOption($key, $value): void
    {
        Assert::keyNotExists($this->options, $key);

        $options = $this->options;
        $options[$key] = $value;

        $this->resolveAndSetOptions($options);
    }

    /**
     * @return array<string,mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param mixed $key
     */
    public function hasOption($key): bool
    {
        return \array_key_exists($key, $this->options);
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function getOption($key)
    {
        if (!$this->hasOption($key)) {
            throw new InvalidArgumentException(sprintf('Option "%s" is not defined', (string) $key));
        }

        return $this->options[$key];
    }

    /**
     * @param array<string|int,mixed> $options
     */
    private function resolveAndSetOptions(array $options): void
    {
        $resolvedOptions = $this->resolver->resolve($options);

        $this->options = $resolvedOptions;
    }
}

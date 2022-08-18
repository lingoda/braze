<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Object\Traits;

use Lingoda\BrazeBundle\Api\Object\Traits\OptionsTrait;
use PhpSpec\ObjectBehavior;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

/**
 * @mixin TestObject
 */
class OptionsTraitSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(TestObject::class);
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TestObject::class);
    }

    function it_creates_with_allowed_options()
    {
        $options = [
            'allowed_int' => 1,
            'allowed_int_or_null' => null,
            'allowed_string' => 'string',
            'allowed_bool' => true,
            'allowed_class' => new TestTypeClass(),
            'allowed_values' => 'value_2',
        ];
        $this->beConstructedWith($options);

        Assert::same($options, $this->getWrappedObject()->getOptions());
    }

    function it_creates_with_not_full_list_of_options()
    {
        $options = [
            'allowed_int' => 1,
        ];
        $this->beConstructedWith($options);

        Assert::same($options, $this->getWrappedObject()->getOptions());
    }

    function it_tries_to_create_with_not_existing_option()
    {
        $options = [
            'fake' => 1,
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(UndefinedOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_tries_to_create_with_invalid_option_int()
    {
        $options = [
            'allowed_int' => 'not an int',
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_tries_to_create_with_invalid_option_int_not_null()
    {
        $options = [
            'allowed_int' => null,
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_tries_to_create_with_invalid_option_int_or_null()
    {
        $options = [
            'allowed_int_or_null' => 'not an int',
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_tries_to_create_with_invalid_option_string()
    {
        $options = [
            'allowed_string' => 1,
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_tries_to_create_with_invalid_option_bool()
    {
        $options = [
            'allowed_bool' => 1,
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_tries_to_create_with_invalid_option_class()
    {
        $options = [
            'allowed_class' => 1,
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_tries_to_create_with_invalid_option_values()
    {
        $options = [
            'allowed_values' => 'invalid_value',
        ];

        $this->beConstructedWith($options);
        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_adds_option()
    {
        $options = [
            'allowed_int' => 1,
        ];

        $this->beConstructedWith($options);
        $this->testAddOption('allowed_bool', false);

        Assert::same([
            'allowed_int' => 1,
            'allowed_bool' => false,
        ], $this->getWrappedObject()->getOptions());
    }

    function it_tries_to_add_not_existing_option()
    {
        $options = [
            'allowed_int' => 1,
        ];
        $this->beConstructedWith($options);

        $this
            ->shouldThrow(UndefinedOptionsException::class)
            ->during('testAddOption', ['fake_option', 1])
        ;
    }

    function it_tries_to_add_invalid_option()
    {
        $options = [
            'allowed_int' => 1,
        ];
        $this->beConstructedWith($options);

        $this
            ->shouldThrow(InvalidOptionsException::class)
            ->during('testAddOption', ['allowed_bool', 1])
        ;
    }
}

class TestObject
{
    use OptionsTrait;

    /**
     * Proxy method to test the protected addOption() on the trait
     *
     * @param mixed $value
     */
    public function testAddOption(string $key, $value): void
    {
        $this->addOption($key, $value);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            'allowed_int',
            'allowed_int_or_null',
            'allowed_string',
            'allowed_bool',
            'allowed_class',
            'allowed_values',
        ]);

        $resolver
            ->setAllowedTypes('allowed_int', ['integer'])
            ->setAllowedTypes('allowed_int_or_null', ['null', 'integer'])
            ->setAllowedTypes('allowed_string', ['string'])
            ->setAllowedTypes('allowed_bool', ['bool'])
            ->setAllowedTypes('allowed_class', [TestTypeClass::class])
            ->setAllowedTypes('allowed_values', ['string'])
        ;

        $resolver
            ->setAllowedValues('allowed_values', ['value_1', 'value_2'])
        ;
    }
}

class TestTypeClass
{
}

<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use PhpSpec\ObjectBehavior;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class UserAttributesSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['braze_id' => new BrazeId('braze-id')]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserAttributes::class);
        $this->getOptions()->shouldHaveKey('braze_id');
    }

    function it_will_throw_exception_with_invalid_option()
    {
        $this->beConstructedWith(['invalid_option' => 'value']);
        $this
            ->shouldThrow(UndefinedOptionsException::class)
            ->duringInstantiation()
        ;
    }

    function it_will_assign_only_defined_options()
    {
        $expectedOptions = [
            'external_id' => new ExternalId('external-id'),
        ];

        $this->beConstructedWith($expectedOptions);
        $this->getOptions()->shouldBeEqualTo($expectedOptions);
    }

    function it_allow_certain_attributes_to_be_deleted()
    {
        $expectedOptions = [
            'braze_id' => new BrazeId('braze-id'),
            'country' => null,
            'current_location' => null,
            'date_of_first_session' => null,
            'date_of_last_session' => null,
            'dob' => null,
            'email' => null,
            'email_subscribe' => null,
            'email_open_tracking_disabled' => null,
            'email_click_tracking_disabled' => null,
            'facebook' => null,
            'first_name' => null,
            'gender' => null,
            'home_city' => null,
            'image_url' => null,
            'language' => null,
            'last_name' => null,
            'marked_email_as_spam_at' => null,
            'phone' => null,
            'push_subscribe' => null,
            'push_tokens' => null,
            'time_zone' => null,
            'twitter' => null,
        ];

        $this->beConstructedWith($expectedOptions);
        $this->getOptions()->shouldBeEqualTo($expectedOptions);
    }

    function it_can_be_initialized_with_options()
    {
        $expectedOptions = [
            'braze_id' => new BrazeId('braze-id'),
            'country' => null,
            'current_location' => null,
            'date_of_first_session' => null,
            'date_of_last_session' => null,
            'dob' => null,
            'email' => null,
            'email_subscribe' => null,
            'email_open_tracking_disabled' => null,
            'email_click_tracking_disabled' => null,
            'facebook' => null,
            'first_name' => null,
            'gender' => null,
            'home_city' => null,
            'image_url' => null,
            'language' => null,
            'last_name' => null,
            'marked_email_as_spam_at' => null,
            'phone' => null,
            'push_subscribe' => null,
            'push_tokens' => null,
            'time_zone' => null,
            'twitter' => null,
        ];

        $this->beConstructedThrough('withOptions', [$expectedOptions]);
        $this->getOptions()->shouldBeEqualTo($expectedOptions);
    }

    function it_can_add_string_custom_attribute()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->setCustomUserAttribute('custom_attribute', 'string');
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => 'string',
        ]);
    }

    function it_can_add_bool_custom_attribute()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->setCustomUserAttribute('custom_attribute', true);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => true,
        ]);
    }

    function it_can_add_float_custom_attribute()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->setCustomUserAttribute('custom_attribute', 0.3);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => 0.3,
        ]);
    }

    function it_can_add_int_custom_attribute()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->setCustomUserAttribute('custom_attribute', 10);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => 10,
        ]);
    }

    function it_can_add_date_custom_attribute()
    {
        $externalId = new ExternalId('external-id');
        $now = CarbonImmutable::now();

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->setCustomUserAttribute('custom_attribute', $now);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => $now,
        ]);
    }

    function it_can_add_array_custom_attribute()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->setCustomUserAttribute('custom_attribute', ['one', 'two']);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => ['one', 'two'],
        ]);
    }

    function it_can_inc_int_custom_attribute()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->incCustomAttribute('custom_attribute', 12);
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => ['inc' => 12],
        ]);
    }

    function it_can_add_to_custom_attribute_array()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->addToCustomAttributeArray('custom_attribute', 'one');
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => ['add' => ['one']],
        ]);
    }

    function it_can_remove_from_custom_attribute_array()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->removeFromCustomAttributeArray('custom_attribute', 'one');
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => ['remove' => ['one']],
        ]);
    }

    function it_throws_exception_when_add_custom_attribute_twice()
    {
        $externalId = new ExternalId('external-id');

        $this->beConstructedWith(['external_id' => $externalId]);
        $this->addToCustomAttributeArray('custom_attribute', 'one');
        $this->getOptions()->shouldBeEqualTo([
            'external_id' => $externalId,
            'custom_attribute' => ['add' => ['one']],
        ]);

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('addToCustomAttributeArray', ['custom_attribute', 'one'])
        ;
    }

    function it_throws_exception_when_user_attribute_is_used_as_custom()
    {
        $this
            ->shouldThrow(new InvalidArgumentException(
                '\'email\' is a default user attribute. Consider using something else for custom attribute name.'
            ))
            ->during('setCustomUserAttribute', ['email', 'value'])
        ;
    }
}

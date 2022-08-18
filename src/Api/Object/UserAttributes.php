<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object;

use Carbon\CarbonInterface;
use Lingoda\BrazeBundle\Api\Constants\Gender;
use Lingoda\BrazeBundle\Api\Constants\LanguageCodes;
use Lingoda\BrazeBundle\Api\Constants\SubscriptionType;
use Lingoda\BrazeBundle\Api\Object\Property\TrackableObjectProperties;
use Lingoda\BrazeBundle\Api\Object\Property\UserAttributesProperties;
use Lingoda\BrazeBundle\Validator\Constraints\AtLeastOneOf;
use Lingoda\BrazeBundle\Validator\Constraints\Sequentially;
use Lingoda\BrazeBundle\Validator\Validation;
use ReflectionClass;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Webmozart\Assert\Assert;

/**
 * Users attributes object
 *
 * @see https://www.braze.com/docs/api/objects_filters/user_attributes_object/
 */
class UserAttributes extends TrackableObject
{
    private const NON_ATTR_FIELDS = [
        TrackableObjectProperties::BRAZE_ID,
        TrackableObjectProperties::EXTERNAL_ID,
        TrackableObjectProperties::USER_ALIAS,
        TrackableObjectProperties::UPDATE_EXISTING_ONLY,
    ];

    /**
     * The maximum number of elements in custom attribute arrays defaults to 25.
     */
    private const CUSTOM_ARRAY_MAX_LENGTH = 25;

    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'country',
            'current_location',
            'date_of_first_session',
            'date_of_last_session',
            'dob',
            'email',
            'email_subscribe',
            'email_open_tracking_disabled',
            'email_click_tracking_disabled',
            'facebook',
            'first_name',
            'gender',
            'home_city',
            'image_url',
            'language',
            'last_name',
            'marked_email_as_spam_at',
            'phone',
            'push_subscribe',
            'push_tokens',
            'time_zone',
            'twitter',
        ]);

        $resolver
            ->setAllowedTypes('country', ['null', 'string'])
            ->setAllowedTypes('current_location', ['null', Location::class])
            ->setAllowedTypes('date_of_first_session', ['null', CarbonInterface::class])
            ->setAllowedTypes('date_of_last_session', ['null', CarbonInterface::class])
            ->setAllowedTypes('dob', ['null', 'string'])
            ->setAllowedTypes('email', ['null', 'string'])
            ->setAllowedTypes('email_subscribe', ['null', 'string'])
            ->setAllowedTypes('email_open_tracking_disabled', ['null', 'bool'])
            ->setAllowedTypes('email_click_tracking_disabled', ['null', 'bool'])
            ->setAllowedTypes('facebook', ['null', Facebook::class])
            ->setAllowedTypes('first_name', ['null', 'string'])
            ->setAllowedTypes('gender', ['null', 'string'])
            ->setAllowedTypes('home_city', ['null', 'string'])
            ->setAllowedTypes('image_url', ['null', 'string'])
            ->setAllowedTypes('language', ['null', 'string'])
            ->setAllowedTypes('last_name', ['null', 'string'])
            ->setAllowedTypes('marked_email_as_spam_at', ['null', CarbonInterface::class])
            ->setAllowedTypes('phone', ['null', 'string'])
            ->setAllowedTypes('push_subscribe', ['null', 'string'])
            ->setAllowedTypes('push_tokens', ['null', PushToken::class . '[]']) // TODO add validation
            ->setAllowedTypes('time_zone', ['null', 'string'])
            ->setAllowedTypes('twitter', ['null', Twitter::class])
        ;

        $resolver
            ->setAllowedValues('gender', Gender::$choices)
            ->setAllowedValues('email_subscribe', SubscriptionType::$choices)
            ->setAllowedValues('language', LanguageCodes::$choices)
        ;
    }

    /**
     * @param int|float|bool|string|array<string>|array<string, mixed>|array<array<string>>|CarbonInterface       $value
     */
    public function setCustomUserAttribute(string $attributeName, $value): void
    {
        Assert::notEmpty($attributeName);

        $this->setCustomUserAttributes([$attributeName => $value]);
    }

    /**
     * @param array<string, int|float|bool|string|array<string>|CarbonInterface> $customAttributes
     */
    public function setCustomUserAttributes(array $customAttributes): void
    {
        foreach ($customAttributes as $customAttribute => $value) {
            $this->addCustomAttributeOption($customAttribute);
        }

        /** @var array<UserAttributesProperties::*, mixed> $resolvedCustomAttributes */
        $resolvedCustomAttributes = $this->resolver->resolve($customAttributes);

        /** @var array<UserAttributesProperties::*, mixed> $mergedOptions */
        $mergedOptions = array_merge(
            $this->getOptions(),
            $resolvedCustomAttributes
        );

        $this->setOptions($mergedOptions);
    }

    /**
     * @phpstan-param UserAttributesProperties::* $attributeName
     */
    public function addToCustomAttributeArray(string $attributeName, string $value): void
    {
        Assert::notEmpty($value);

        $this->setCustomUserAttribute($attributeName, [
            'add' => [$value],
        ]);
    }

    /**
     * @phpstan-param UserAttributesProperties::* $attributeName
     */
    public function removeFromCustomAttributeArray(string $attributeName, string $value): void
    {
        Assert::notEmpty($value);

        $this->setCustomUserAttribute($attributeName, [
            'remove' => [$value],
        ]);
    }

    /**
     * @phpstan-param UserAttributesProperties::* $attributeName
     */
    public function incCustomAttribute(string $attributeName, int $value): void
    {
        Assert::notEmpty($attributeName);

        $this->setCustomUserAttribute($attributeName, ['inc' => $value]);
    }

    /**
     * Check if there is any attribute for update
     */
    public function hasAttributes(): bool
    {
        return !empty(array_filter(
            $this->getOptions(),
            static fn ($v, $k) => !\in_array($k, self::NON_ATTR_FIELDS, true),
            \ARRAY_FILTER_USE_BOTH
        ));
    }

    /**
     * @param array<UserAttributesProperties::*, mixed> $options
     *
     * @return static
     */
    public static function withOptions(array $options): self
    {
        return new static($options);
    }

    /**
     * @return callable(mixed, ConstraintViolationListInterface): bool
     */
    protected function createCustomAttributesValidationCallback(): callable
    {
        return Validation::createIsValidCallback(
            new AtLeastOneOf([
                new Type([
                    'bool',
                    'int',
                    'float',
                    'string',
                    CarbonInterface::class,
                ]),
                new Sequentially([
                    new Type('array'),
                    new Count(['max' => self::CUSTOM_ARRAY_MAX_LENGTH]),
                    new Callback(static fn ($payload): bool => !\is_array($payload)),
                ]),
            ])
        );
    }

    protected function addCustomAttributeOption(string $attribute): void
    {
        Assert::false(
            \in_array($attribute, $this->getDefaultUserAttributes(), true),
            "'${attribute}' is a default user attribute. Consider using something else for custom attribute name."
        );

        Assert::keyNotExists($this->getOptions(), $attribute);

        $this->resolver->setDefined($attribute);
        $this->resolver->setAllowedValues($attribute, $this->createCustomAttributesValidationCallback());
    }

    /**
     * Default user attributes defined by Braze
     *
     * @return array<UserAttributesProperties::*>
     */
    private function getDefaultUserAttributes(): array
    {
        return (new ReflectionClass(UserAttributesProperties::class))
            ->getConstants()
            ;
    }
}

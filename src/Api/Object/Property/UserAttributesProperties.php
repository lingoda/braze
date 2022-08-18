<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object\Property;

/**
 * User Attribute Object properties
 */
interface UserAttributesProperties extends TrackableObjectProperties
{
    public const COUNTRY = 'country';
    public const CURRENT_LOCATION = 'current_location';
    public const DATE_OF_FIRST_SESSION = 'date_of_first_session';
    public const DATE_OF_LAST_SESSION = 'date_of_last_session';
    public const DOB = 'dob';
    public const EMAIL = 'email';
    public const EMAIL_SUBSCRIBE = 'email_subscribe';
    public const EMAIL_OPEN_TRACKING_DISABLED = 'email_open_tracking_disabled';
    public const EMAIL_CLICK_TRACKING_DISABLED = 'email_click_tracking_disabled';
    public const FACEBOOK = 'facebook';
    public const FIRST_NAME = 'first_name';
    public const GENDER = 'gender';
    public const HOME_CITY = 'home_city';
    public const IMAGE_URL = 'image_url';
    public const LANGUAGE = 'language';
    public const LAST_NAME = 'last_name';
    public const MARKED_EMAIL_AS_SPAM_AT = 'marked_email_as_spam_at';
    public const PHONE = 'phone';
    public const PUSH_SUBSCRIBE = 'push_subscribe';
    public const PUSH_TOKENS = 'push_tokens';
    public const TIME_ZONE = 'time_zone';
    public const TWITTER = 'twitter';
}

<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Endpoint;

use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\BrazeClientInterface;
use Lingoda\BrazeBundle\Api\Delta\UserAttributesDeltaResolver;
use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Lingoda\BrazeBundle\Api\Object\AliasToIdentify;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Purchase;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Api\Request\TrackUserDataRequest;
use Lingoda\BrazeBundle\Api\Request\TrackUserDataWithoutDeltaRequest;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;
use Lingoda\BrazeBundle\Api\Response\ApiUserDeleteResponse;
use Lingoda\BrazeBundle\Api\Response\ApiUserTrackResponse;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface as OptionResolverException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

/**
 * Users Data Endpoints
 *
 * @see https://www.braze.com/docs/api/endpoints/user_data/
 */
class Users extends Endpoint
{
    private const BRAZE_DELETE_OBJECT_LIMIT = 50;
    private const BASE_ENDPOINT = 'users';

    private UserAttributesDeltaResolver $userAttributesDeltaResolver;

    public function __construct(BrazeClientInterface $client, UserAttributesDeltaResolver $userAttributesDeltaResolver)
    {
        parent::__construct($client);
        $this->userAttributesDeltaResolver = $userAttributesDeltaResolver;
    }

    /**
     * Use this endpoint to record custom events, purchases, and update user profile attributes.
     *
     * @see https://www.braze.com/docs/api/endpoints/user_data/post_user_track/
     *
     * @throws HttpException
     */
    public function track(TrackUserDataRequest $trackUserDataRequest): ApiUserTrackResponse
    {
        $trackUserDataRequest = $this->applyDeltaAttributes($trackUserDataRequest);
        if (!$trackUserDataRequest) {
            return new ApiUserTrackResponse(); // there was only attribute update request
        }

        $response = $this->trackRequest($trackUserDataRequest);
        $this->storeDeltaAttributes($trackUserDataRequest);

        return $response;
    }

    /**
     * Helper method for tracking user events
     *
     * @throws HttpException
     */
    public function trackEvents(Event ...$event): ApiUserTrackResponse
    {
        return $this->track(TrackUserDataRequest::withEvents($event));
    }

    /**
     * Helper method for tracking user attributes
     *
     * @throws HttpException
     */
    public function trackAttributes(UserAttributes ...$attribute): ApiUserTrackResponse
    {
        return $this->track(TrackUserDataRequest::withAttributes($attribute));
    }

    /**
     * Helper method for tracking purchases
     *
     * @throws HttpException
     */
    public function trackPurchases(Purchase ...$purchase): ApiUserTrackResponse
    {
        return $this->track(TrackUserDataRequest::withPurchases($purchase));
    }

    /**
     * Use this endpoint to identify an unidentified (alias-only) user.
     *
     * @see https://www.braze.com/docs/api/endpoints/user_data/post_user_identify/
     *
     * @throws HttpException
     */
    public function identify(AliasToIdentify ...$aliasToIdentify): ApiResponseInterface
    {
        return $this->client->post(self::BASE_ENDPOINT . '/identify', [
            'aliases_to_identify' => $aliasToIdentify,
        ]);
    }

    /**
     * Use this endpoint to add new user aliases for existing identified users, or to create new unidentified users.
     *
     * @see https://www.braze.com/docs/api/endpoints/user_data/post_user_alias/
     *
     * @throws HttpException
     */
    public function addAlias(UserAlias ...$userAlias): ApiResponseInterface
    {
        return $this->client->post(self::BASE_ENDPOINT . '/alias/new', [
            'user_aliases' => $userAlias,
        ]);
    }

    /**
     * Helper method for deleting user profiles by external ids
     *
     * @throws HttpException
     */
    public function deleteByExternalIds(ExternalId ...$externalIds): ApiUserDeleteResponse
    {
        return $this->delete([
            'external_ids' => $externalIds,
        ]);
    }

    /**
     * Helper method for deleting user profiles by user aliases
     *
     * @throws HttpException
     */
    public function deleteByUserAliases(UserAlias ...$userAliases): ApiUserDeleteResponse
    {
        return $this->delete([
            'user_aliases' => $userAliases,
        ]);
    }

    /**
     * Helper method for deleting user profiles by braze ids
     *
     * @throws HttpException
     */
    public function deleteByBrazeIds(BrazeId ...$brazeIds): ApiUserDeleteResponse
    {
        return $this->delete([
            'braze_ids' => $brazeIds,
        ]);
    }

    /**
     * This endpoint allows you to delete any user profile by specifying a known user identifier.
     * Only one of external_ids, user_aliases, or braze_ids can be included in a single request.
     *
     * @see https://www.braze.com/docs/api/endpoints/user_data/post_user_delete/
     *
     * @param array{external_ids?: ExternalId[]|string[], user_aliases?: UserAlias[]|string[], braze_ids?: BrazeId[]|string[]} $options
     *
     * @throws HttpException
     */
    public function delete(array $options = []): ApiUserDeleteResponse
    {
        $resolver = (new OptionsResolver())
            ->setDefined([
                'external_ids',
                'user_aliases',
                'braze_ids',
            ])
            ->setAllowedTypes('external_ids', ['string[]', ExternalId::class . '[]'])
            ->setAllowedTypes('user_aliases', ['string[]', UserAlias::class . '[]'])
            ->setAllowedTypes('braze_ids', ['string[]', BrazeId::class . '[]'])
        ;

        $options = $resolver->resolve($options);

        Assert::count(
            $options,
            1,
            'Only one of "external_ids" or "user_aliases" or "braze_ids" can be included in a single request.'
        );
        self::assertDeleteObjectLimit($options, 'external_ids');
        self::assertDeleteObjectLimit($options, 'user_aliases');
        self::assertDeleteObjectLimit($options, 'braze_ids');

        $response = $this->client->post(self::BASE_ENDPOINT . '/delete', $options, ApiUserDeleteResponse::class);
        Assert::isInstanceOf($response, ApiUserDeleteResponse::class);

        return $response;
    }

    private function storeDeltaAttributes(TrackUserDataRequest $trackUserDataRequest): void
    {
        if ($trackUserDataRequest instanceof TrackUserDataWithoutDeltaRequest || !$trackUserDataRequest->hasAttributes()) {
            return;
        }

        foreach ($trackUserDataRequest->getAttributes() as $userAttributes) {
            $this->userAttributesDeltaResolver->storeDeltaAttributes($userAttributes);
        }
    }

    /**
     * @throws HttpException
     */
    private function trackRequest(TrackUserDataRequest $trackUserDataRequest): ApiUserTrackResponse
    {
        $response = $this->client->post(
            self::BASE_ENDPOINT . '/track',
            $trackUserDataRequest->getOptions(),
            ApiUserTrackResponse::class
        );
        Assert::isInstanceOf($response, ApiUserTrackResponse::class);

        return $response;
    }

    private function applyDeltaAttributes(TrackUserDataRequest $trackUserDataRequest): ?TrackUserDataRequest
    {
        if ($trackUserDataRequest instanceof TrackUserDataWithoutDeltaRequest || !$trackUserDataRequest->hasAttributes()) {
            return $trackUserDataRequest;
        }

        $deltaAttributes = array_values(array_filter(
            $this->userAttributesDeltaResolver->resolveAll($trackUserDataRequest->getAttributes()),
            static fn (UserAttributes $userAttributes) => $userAttributes->hasAttributes()
        )); // use array_values to reset the keys otherwise serializer will serialize as an object rather than array

        $options = $trackUserDataRequest->getOptions();
        if (!empty($deltaAttributes)) {
            $options['attributes'] = $deltaAttributes;
        } else {
            unset($options['attributes']);
        }

        try {
            return $trackUserDataRequest->withOptions($options);
        } catch (InvalidArgumentException|OptionResolverException $e) {
            return null; // if there are no events or purchases left
        }
    }

    /**
     * @param array{external_ids?: ExternalId[], user_aliases?: UserAlias[], braze_ids?: BrazeId[]} $options
     */
    private static function assertDeleteObjectLimit(array $options, string $identifiers): void
    {
        Assert::nullOrMaxCount(
            $options[$identifiers] ?? null,
            self::BRAZE_DELETE_OBJECT_LIMIT,
            sprintf('Up to %d %s can be included in a single request', self::BRAZE_DELETE_OBJECT_LIMIT, $identifiers)
        );
    }
}

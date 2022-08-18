<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Endpoint;

use InvalidArgumentException;
use Lingoda\BrazeBundle\Api\BrazeClientInterface;
use Lingoda\BrazeBundle\Api\Delta\UserAttributesDeltaResolver;
use Lingoda\BrazeBundle\Api\Endpoint\Users;
use Lingoda\BrazeBundle\Api\Object\AppId;
use Lingoda\BrazeBundle\Api\Object\BrazeId;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Purchase;
use Lingoda\BrazeBundle\Api\Object\UserAlias;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use Lingoda\BrazeBundle\Api\Request\TrackUserDataRequest;
use Lingoda\BrazeBundle\Api\Response\ApiResponseInterface;
use Lingoda\BrazeBundle\Api\Response\ApiUserDeleteResponse;
use Lingoda\BrazeBundle\Api\Response\ApiUserTrackResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UsersSpec extends ObjectBehavior
{
    function let(
        BrazeClientInterface $brazeClient,
        ApiResponseInterface $response,
        UserAttributesDeltaResolver $userAttributesDeltaResolver
    ) {
        $this->beConstructedWith($brazeClient, $userAttributesDeltaResolver);

        $brazeClient->post(Argument::any(), Argument::any(), Argument::any())->willReturn($response);
        $brazeClient->delete(Argument::any(), Argument::any(), Argument::any())->willReturn($response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Users::class);
    }

    function it_can_track_user_data(
        BrazeClientInterface $brazeClient,
        ApiUserTrackResponse $apiUserTrackResponse,
        UserAttributesDeltaResolver $userAttributesDeltaResolver
    ) {
        $options = [
            'attributes' => [
                new UserAttributes([
                    'external_id' => new ExternalId('id'),
                    'email' => 'test@lingoda.com',
                ]),
            ],
            'events' => [
                new Event([
                    'external_id' => new ExternalId('id'),
                    'name' => 'custom_event',
                ]),
            ],
            'purchases' => [
                new Purchase([
                    'external_id' => new ExternalId('id'),
                    'app_id' => new AppId('930FB07C-CE36-4282-BE16-15A00F657CD2'),
                    'product_id' => 'product_id',
                    'price' => 10.0,
                    'currency' => 'EUR',
                ]),
            ],
        ];

        $userAttributesDeltaResolver->resolveAll(Argument::any())->willReturn($options['attributes']);
        $userAttributesDeltaResolver->storeDeltaAttributes(Argument::cetera())->shouldBeCalledOnce();

        $brazeClient->post('users/track', Argument::type('array'), ApiUserTrackResponse::class)
            ->willReturn($apiUserTrackResponse)
            ->shouldBeCalledOnce()
        ;

        $this->track(new TrackUserDataRequest($options));
    }

    function it_can_create_user_alias(BrazeClientInterface $brazeClient, UserAlias $userAlias)
    {
        $brazeClient->post('users/alias/new', [
            'user_aliases' => [$userAlias],
        ])->shouldBeCalledOnce();

        $this->addAlias($userAlias);
    }

    function it_throws_exception_when_deleting_options_are_invalid()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('Only one of "external_ids" or "user_aliases" or "braze_ids" can be included in a single request.'))
            ->during('delete', [[]])
        ;
    }

    function it_throws_exception_when_multiple_identifiers_are_defined_on_deletion()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('Only one of "external_ids" or "user_aliases" or "braze_ids" can be included in a single request.'))
            ->during('delete', [
                [
                    'external_ids' => ['external_id'],
                    'user_aliases' => ['user_alias'],
                ],
            ])
        ;
    }

    function it_throws_exception_when_deleting_above_upper_limit_with_external_ids()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('Up to 50 external_ids can be included in a single request'))
            ->during('delete', [['external_ids' => array_fill(0, 51, 'external_id')]])
        ;
    }

    function it_throws_exception_when_deleting_above_upper_limit_with_user_aliases()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('Up to 50 user_aliases can be included in a single request'))
            ->during('delete', [['user_aliases' => array_fill(0, 51, 'user_alias')]])
        ;
    }

    function it_throws_exception_when_deleting_above_upper_limit_with_braze_ids()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('Up to 50 braze_ids can be included in a single request'))
            ->during('delete', [['braze_ids' => array_fill(0, 51, 'braze_id')]])
        ;
    }

    function it_can_delete_by_external_id(BrazeClientInterface $brazeClient, ApiUserDeleteResponse $userDeleteResponse)
    {
        $externalId1 = new ExternalId('external-id-1');
        $externalId2 = new ExternalId('external-id-2');

        $brazeClient
            ->post('users/delete', [
                'external_ids' => [$externalId1, $externalId2],
            ], ApiUserDeleteResponse::class)
            ->willReturn($userDeleteResponse)
            ->shouldBeCalledOnce()
        ;

        $this->deleteByExternalIds($externalId1, $externalId2);
    }

    function it_can_delete_by_user_aliases(BrazeClientInterface $brazeClient, ApiUserDeleteResponse $userDeleteResponse)
    {
        $alias1 = new UserAlias('alias', 'label');
        $alias2 = new UserAlias('alias', 'label');

        $brazeClient
            ->post('users/delete', [
                'user_aliases' => [$alias1, $alias2],
            ], ApiUserDeleteResponse::class)
            ->willReturn($userDeleteResponse)
            ->shouldBeCalledOnce()
        ;

        $this->deleteByUserAliases($alias1, $alias2);
    }

    function it_can_delete_by_braze_ids(BrazeClientInterface $brazeClient, ApiUserDeleteResponse $userDeleteResponse)
    {
        $brazeId1 = new BrazeId('braze-id-1');
        $brazeId2 = new BrazeId('braze-id-2');

        $brazeClient
            ->post('users/delete', [
                'braze_ids' => [$brazeId1, $brazeId2],
            ], ApiUserDeleteResponse::class)
            ->willReturn($userDeleteResponse)
            ->shouldBeCalledOnce()
        ;

        $this->deleteByBrazeIds($brazeId1, $brazeId2);
    }

    function it_can_delete(BrazeClientInterface $brazeClient, ApiUserDeleteResponse $userDeleteResponse)
    {
        // delete by external_ids
        $externalId = new ExternalId('external-id');
        $brazeClient
            ->post(
                'users/delete',
                [
                    'external_ids' => [$externalId],
                ],
                ApiUserDeleteResponse::class
            )
            ->willReturn($userDeleteResponse)
            ->shouldBeCalledOnce()
        ;

        $this->delete([
            'external_ids' => [$externalId],
        ]);

        // delete by braze_ids
        $brazeId = new BrazeId('braze-id');
        $brazeClient
            ->post(
                'users/delete',
                [
                    'braze_ids' => [$brazeId],
                ],
                ApiUserDeleteResponse::class
            )
            ->willReturn($userDeleteResponse)
            ->shouldBeCalledOnce()
        ;

        $this->delete([
            'braze_ids' => [$brazeId],
        ]);

        // delete by user_aliases
        $userAlias = new UserAlias('name', 'label');
        $brazeClient
            ->post(
                'users/delete',
                [
                    'user_aliases' => [$userAlias],
                ],
                ApiUserDeleteResponse::class
            )
            ->willReturn($userDeleteResponse)
            ->shouldBeCalledOnce()
        ;

        $this->delete([
            'user_aliases' => [$userAlias],
        ]);
    }

    function it_resolves_delta_correctly(
        BrazeClientInterface $brazeClient,
        ApiUserTrackResponse $apiUserTrackResponse,
        UserAttributesDeltaResolver $userAttributesDeltaResolver
    ) {
        $attr1 = new UserAttributes([
            'external_id' => new ExternalId('1'),
        ]);
        $attr2 = new UserAttributes([
            'external_id' => new ExternalId('2'),
            'email' => 'test2@lingoda.com',
        ]);

        $userAttributesDeltaResolver->resolveAll([$attr1, $attr2])->willReturn([$attr1, $attr2]);
        $userAttributesDeltaResolver->storeDeltaAttributes(Argument::cetera())->shouldBeCalledOnce();

        $brazeClient
            ->post(
                'users/track',
                Argument::that(function (array $options) use ($attr2) {
                    // checking if indexing if still ok after filtering out attr1
                    return isset($options['attributes']) && \count($options['attributes']) === 1 && $options['attributes'][0] === $attr2;
                }),
                ApiUserTrackResponse::class
            )
            ->willReturn($apiUserTrackResponse)
            ->shouldBeCalledOnce()
        ;

        $this->trackAttributes($attr1, $attr2);
    }
}

<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Endpoint;

use Lingoda\BrazeBundle\Api\BrazeClientInterface;
use Lingoda\BrazeBundle\Api\Endpoint\Export;
use Lingoda\BrazeBundle\Api\Request\ExportUsersByIdentifiersRequest;
use Lingoda\BrazeBundle\Api\Response\ApiUserExportResponse;
use PhpSpec\ObjectBehavior;

class ExportSpec extends ObjectBehavior
{
    function let(BrazeClientInterface $brazeClient)
    {
        $this->beConstructedWith($brazeClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Export::class);
    }

    function it_can_export_by_user_ids(
        ExportUsersByIdentifiersRequest $request,
        BrazeClientInterface $brazeClient,
        ApiUserExportResponse $response
    ) {
        $request->getOptions()->willReturn(['braze_id' => '123']);
        $brazeClient
            ->post('users/export/ids', ['braze_id' => '123'], ApiUserExportResponse::class)
            ->willReturn($response)
            ->shouldBeCalledOnce()
        ;

        $this->usersByIds($request);
    }
}

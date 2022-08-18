<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Endpoint;

use Lingoda\BrazeBundle\Api\Exception\HttpException;
use Lingoda\BrazeBundle\Api\Request\ExportUsersByIdentifiersRequest;
use Lingoda\BrazeBundle\Api\Response\ApiUserExportResponse;
use Webmozart\Assert\Assert;

/**
 * Export endpoints
 */
class Export extends Endpoint
{
    /**
     * @throws HttpException
     */
    public function usersByIds(ExportUsersByIdentifiersRequest $request): ApiUserExportResponse
    {
        $response = $this->client->post(
            'users/export/ids',
            $request->getOptions(),
            ApiUserExportResponse::class
        );

        Assert::isInstanceOf($response, ApiUserExportResponse::class);

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace ApiCaller;

use Psr\Http\Message\{RequestFactoryInterface, ResponseInterface};
use Psr\Http\Client\ClientInterface;

class Caller
{
    private RequestBuilder $requestBuilder;
    
    public function __construct(private ClientInterface $client, private RequestFactoryInterface $factory)
    {
        $this->requestBuilder = new RequestBuilder($this->factory);
    }

    public function callApi(array $conf): ResponseInterface
    {
        return $this->client->sendRequest($this->requestBuilder->buildRequest(new RequestConf($conf)));
    }
}

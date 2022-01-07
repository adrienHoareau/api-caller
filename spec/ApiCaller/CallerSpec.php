<?php

namespace spec\ApiCaller;

use ApiCaller\RequestBuilder;
use ApiCaller\RequestConf;
use Nyholm\Psr7\Factory\Psr17Factory;
use PhpSpec\ObjectBehavior;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class CallerSpec extends ObjectBehavior
{
    private const GET_REQUEST_CONF = [
        'contentType' => 'application/json',
        'uri' => 'https://httpstat.us/200',
        'headers' => ['header1' => 'value1', 'header2' => 'value2'],
        'method' => 'GET',
        'timeout' => '10',
    ];

    function it_ensures_callApi_returned_value_to_always_be_ResponseInterface(ClientInterface $client, ResponseInterface $responseI)
    {
        $psr17Factory = new Psr17Factory();
        $builder = new RequestBuilder($psr17Factory);
        $requestConf = new RequestConf(self::GET_REQUEST_CONF);
        $client->sendRequest($builder->buildRequest($requestConf))->willReturn($responseI);
        $this->beConstructedWith($client, $psr17Factory);
        $response = $this->callApi(self::GET_REQUEST_CONF);
        $response->shouldBeAnInstanceOf(ResponseInterface::class);
    }
}

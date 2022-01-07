<?php

namespace ApiCaller;

use Psr\Http\Message\{RequestFactoryInterface, RequestInterface};

class RequestBuilder
{
    public function __construct(private RequestFactoryInterface $factory)
    {
    }
    
    public function buildRequest(RequestConf $requestConf): RequestInterface
    {
        $request = $this->factory->createRequest($requestConf->getMethod(), $requestConf->getUri());
        foreach ($requestConf->getHeaders() as $header) {
            $request = $request->withAddedHeader($header->name, $header->value);
        }
        if ($requestConf->hasBody()) {
            $stream = $this->factory->createStream($requestConf->getBody());
            $stream->rewind();
            $request = $request->withBody($stream);
        }
        
        return $request;
    }
}

<?php

namespace spec\ApiCaller;

use PhpSpec\ObjectBehavior;

class RequestConfSpec extends ObjectBehavior
{
    private const REQUEST_CONF = [
        'uri' => 'https://httpstat.us/200',
        'headers' => ['Content-Type' => 'application/json', 'header1' => 'value1'],
        'method' => 'POST',
        'timeout' => '10',
    ];

    function it_is_filled_from_empty_json()
    {
        $this->beConstructedWith([]);
        $this->shouldThrow('\RuntimeException')->duringInstantiation();
    }
    
    function it_is_filled_with_missing_uri()
    {
        $conf = self::REQUEST_CONF;
        unset($conf['uri']);
        $this->beConstructedWith($conf);
        $this->shouldThrow('\OutOfBoundsException')->duringInstantiation();
    }
    
    function it_is_filled_with_missing_method()
    {
        $conf = self::REQUEST_CONF;
        unset($conf['method']);
        $this->beConstructedWith($conf);
        $this->shouldThrow('\OutOfBoundsException')->duringInstantiation();
    }
    
    function it_is_filled_with_wrong_timeout()
    {
        $conf = self::REQUEST_CONF;
        $conf['timeout'] = 'abc';
        $this->beConstructedWith($conf);
        $this->shouldThrow('\UnexpectedValueException')->duringInstantiation();
    }
    
    function it_is_filled_with_wrong_body()
    {
        $conf = self::REQUEST_CONF;
        $conf['body'] = 123;
        $this->beConstructedWith($conf);
        $this->shouldThrow('\UnexpectedValueException')->duringInstantiation();
    }

    function it_is_filled_with_body_and_json()
    {
        $conf = self::REQUEST_CONF;
        $conf['body'] = ['test'=>'test'];
        $conf['json'] = 123;
        $this->beConstructedWith($conf);
        $this->shouldThrow('\LogicException')->duringInstantiation();
    }
}

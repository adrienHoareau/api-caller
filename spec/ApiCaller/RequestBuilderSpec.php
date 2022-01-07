<?php

namespace spec\ApiCaller;

use ApiCaller\RequestConf;
use Nyholm\Psr7\Factory\Psr17Factory;
use PhpSpec\ObjectBehavior;

class RequestBuilderSpec extends ObjectBehavior
{
    private const GET_REQUEST_CONF = [
        'contentType' => 'application/json',
        'uri' => 'https://httpstat.us/200',
        'headers' => ['header1' => 'value1', 'header2' => 'value2'],
        'method' => 'GET',
        'timeout' => '10',
    ];
    
    private const POST_REQUEST_CONF = [
        'contentType' => 'application/json',
        'uri' => 'https://httpstat.us/200',
        'headers' => ['header1' => 'value1', 'header2' => 'value2'],
        'method' => 'POST',
        'timeout' => '10',
    ];
    
    function let()
    {
        $this->beConstructedWith(new Psr17Factory());
    }
    
    function it_creates_a_get_request()
    {
        $this->creates_spec_request(self::GET_REQUEST_CONF);
    }
    
    function it_creates_a_post_request()
    {
        $conf = self::POST_REQUEST_CONF;
        $conf['body'] = [
            'foo' => 'bar',
            'baz' => 'bat',
        ];
        $request = $this->creates_spec_request($conf);
        $request->getBody()->getContents()->shouldReturn('foo=bar&baz=bat');
    }
    
    function it_creates_a_json_post_request()
    {
        $conf = self::POST_REQUEST_CONF;
        $conf['json'] = [
            'foo' => 'bar',
            'baz' => 'bat',
        ];
        /** @var RequestInterface $request */
        $request = $this->creates_spec_request($conf);
        $request->getBody()->getContents()->shouldReturn('{"foo":"bar","baz":"bat"}');
    }
    
    function it_creates_a_xml_post_request()
    {
        $expected = <<<'EOT'
<note>
    <to>Tove</to>
    <from>Jani</from>
    <heading>Reminder</heading>
    <body>Don't forget me this weekend!</body>
</note>
EOT;
        $conf = self::POST_REQUEST_CONF;
        $conf['body'] = $expected;
        /** @var RequestInterface $request */
        $request = $this->creates_spec_request($conf);
        $request->getBody()->getContents()->shouldReturn($expected);
    }
    
    private function creates_spec_request(array $conf)
    {
        $requestConf = new RequestConf($conf);
        /** @var RequestInterface $request */
        $request = $this->buildRequest($requestConf);
        foreach ($conf['headers'] as $header => $value) {
            $request->getHeader($header)->shouldReturn([$value]);
        }
        
        return $request;
    }
}

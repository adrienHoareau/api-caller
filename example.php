<?php

require __DIR__ . '/vendor/autoload.php';

$conf = [
    'contentType' => 'application/json',
    'uri' => 'https://reqbin.com/echo/post/json',
    'headers' => ['Authorization' => 'Bearer 1234'],
    'method' => 'POST',
    'timeout' => '10',
    'json' => [
        'Id' => 78912,
        'Customer'=> 'Jason Sweet',
        'Quantity'=> 1,
        'Price'=> 18.00,
    ],
];

$factory = new Nyholm\Psr7\Factory\Psr17Factory();
$client = new Symfony\Component\HttpClient\Psr18Client(null, $factory, $factory);
$caller = new ApiCaller\Caller($client, $factory);
$response = $caller->callApi($conf);
echo $response->getBody()->getContents().PHP_EOL;
<?php

declare(strict_types=1);

namespace ApiCaller;

class RequestHeader
{
    public function __construct(public string $name, public string $value)
    {
    }
}

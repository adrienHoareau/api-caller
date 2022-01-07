<?php

declare(strict_types=1);

namespace ApiCaller;

class RequestConf
{
    private string $uri;
    /**
     * @var array<int, RequestHeader>
     */
    private array $headers = [];
    private string $method;
    private int $timeout = 15;
    private ?string $body = null;
    
    public function __construct(array $config)
    {
        if(empty($config)) {
            throw new \RuntimeException('request config cannot be empty');
        }
        $this->uri = $config['uri'] ?? throw new \OutOfBoundsException("mising property `request.uri`");
        $this->method = $config['method'] ?? throw new \OutOfBoundsException("mising property `request.method`");
        if (!empty($config['headers'])) {
            $this->addHeaders($config['headers']);
        }
        if(isset($config['timeout'])) {
            $this->guardAgainstInvalidTimeout($config['timeout']);
            $this->timeout = (int) $config['timeout'];
        }
        if(isset($config['body'])) {
            $this->setBody($config['body']);
        }
        if(isset($config['json'])) {
            $this->setBody(json_encode($config['json'], JSON_THROW_ON_ERROR));
        }
    }
    
    private function addHeaders(array $headers): void
    {
        foreach ($headers as $key => $value) {
            $this->headers[] = new RequestHeader($key, $value);
        }
    }
    
    private function guardAgainstInvalidTimeout(mixed $timeout): void
    {
        if(! is_numeric($timeout)) {
            throw new \UnexpectedValueException('optional timeout setting must be a numeric value');
        }
    }
    
    private function setBody(mixed $content): void
    {
        if (null !== $this->body) {
            throw new \LogicException('body has already been set');
        }
        if (is_array($content)) {
            $this->body = http_build_query($content);
            return;
        }
        if (is_string($content)) {
            $this->body = $content;
            return;
        }
        
        throw new \UnexpectedValueException('optional body content must be an array or a string, '.gettype($content).' given.');
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array<int, RequestHeader>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
    
    public function hasBody(): bool
    {
        return null !== $this->body;
    }
    
    public function getBody(): string
    {
        return $this->body;
    }
}

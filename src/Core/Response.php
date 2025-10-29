<?php
namespace Src\Core;

class Response
{
    protected int $statusCode = 200;
    protected string $body = '';

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setBody(string $content): self
    {
        $this->body = $content;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        echo $this->body;
    }
}

<?php 

namespace WillyFramework\src\Core;

class Response {
    private int $statusCode = 200;
    private array $headers = [];
    private string $body;

    public function setStatus(int $code): self {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader(string $key, string $value): self {
        $this->headers[$key] = $value;
        return $this;
    }

    public function json(array $data): self {
        $this->setHeader('Content-Type', 'application/json');
        $this->body = json_encode($data, JSON_PRETTY_PRINT);
        $this->send();
        return $this;
    }

    public function text(string $text): self {
        $this->setHeader('Content-Type', 'text/plain');
        $this->body = $text;
        $this->send();
        return $this;
    }

    public function html(string $html): self {
        $this->setHeader('Content-Type', 'text/html');
        $this->body = $html;
        $this->send();
        return $this;
    }

    private function send(): void {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
}
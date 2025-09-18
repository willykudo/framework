<?php 

namespace WillyFramework\src\Core;

class Request {
    private string $method;
    private string $uri;
    private array $queryParams;
    private array $headers;
    private array $body;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = strtok($_SERVER['REQUEST_URI'] ?? '/' , '?');
        $this->queryParams = $_GET ?? [];
        $this->body = $_POST;

        if (empty($this->body)) {
            $input = file_get_contents('php://input');
            $decoded = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->body = $decoded;
            }
        }

        $this->headers = getallheaders() ?: [];
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getQueryParams(): array {
        return $this->queryParams;
    }

    public function getBody(): array {
        return $this->body;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function input(string $key, $default = null) {
        return $this->body[$key] ?? $this->queryParams[$key] ?? $default;
    }
}

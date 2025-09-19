<?php 

namespace WillyFramework\src\Router;

use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;

class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function resolve(Request $request, Response $response): void {
        $method = $request->getMethod();
        $uri = $request->getUri();

        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            $response->setStatus(404)->json([
                'error'=>'Route not found',
                'method'=> $method,
                'uri' => $uri
            ]);
            return;
        }

        if(is_callable($handler)) {
            call_user_func($handler, $request, $response);
            return;
        }

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $controllerInstance = new $controller();
            call_user_func([$controllerInstance, $method], $request, $response);
            return;
        }

        $response->setStatus(500)->json(['error' => 'Invalid route handler']);
    }
}
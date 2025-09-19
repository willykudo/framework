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

    public function put(string $path, callable $handler) {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, callable $handler) {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function resolve(Request $request, Response $response): void {
        $method = $request->getMethod();
        $uri = $request->getUri();

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $path => $handler) {
            // bedain {id} dengan parameter lain
            $pattern = preg_replace_callback(
                '#\{([^/]+)\}#',
                function ($matches) {
                    return $matches[1] === 'id' ? '(\d+)' : '([^/]+)';
                },
                $path
            );

            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // buang full match
                
                if (is_callable($handler)) {
                    call_user_func_array($handler, array_merge([$request, $response], $matches));
                    return;
                }

                if (is_array($handler)) {
                    [$controller, $method] = $handler;
                    $controllerInstance = new $controller();
                    call_user_func_array([$controllerInstance, $method], array_merge([$request, $response], $matches));
                    return;
                }
            }
        }
       
        $response->setStatus(404)->json([
            'error'  => 'Route not found',
            'method' => $method,
            'uri'    => $uri
        ]);
    }
}

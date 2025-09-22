<?php 

namespace WillyFramework\src\Router;

use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;

class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler, array $middlewares = []) {
        $this->routes['GET'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function post(string $path, callable|array $handler, array $middlewares = []) {
        $this->routes['POST'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public function put(string $path, callable $handler, array $middlewares = []) {
        $this->routes['PUT'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function delete(string $path, callable $handler, array $middlewares = []) {
        $this->routes['DELETE'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function resolve(Request $request, Response $response) {
        $method = $request->getMethod();
        $uri = $request->getUri();

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $path => $route) {
            $handler = $route['handler'];
            $middlewares = $route['middlewares'];

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
                array_shift($matches); 
                
                $next = function($request, $response) use ($handler, $matches){
                    if (is_callable($handler)) {
                        call_user_func_array($handler, array_merge([$request, $response], $matches));
                        return;
                    }

                    if (is_array($handler)) {
                        [$controller, $method] = $handler;
                        $controllerInstance = new $controller();
                        return call_user_func_array([$controllerInstance, $method], array_merge([$request, $response], $matches));  
                    }
                };

                foreach (array_reverse($middlewares) as $middleware) {
                    $next = function($request, $response) use ($middleware, $next) {
                        $instance = new $middleware();
                        return $instance->handle($request, $response, $next);
                    };
                }

                return $next($request, $response);
            };
        }
       
        $response->setStatus(404)->json([
            'error'  => 'Route not found',
            'method' => $method,
            'uri'    => $uri
        ]);
    }
}

<?php 

namespace WillyFramework\src\Router;

use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;
use WillyFramework\src\Core\Container;
use WillyFramework\pkg\ExceptionHandler;

class Router {
    private array $routes = [];
    private Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function addRoute(string $httpMethod, string $path, callable|array $handler, array $middlewares = []): void {
        $this->routes[strtoupper($httpMethod)][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, callable|array $handler, array $middlewares=[]): void {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, callable|array $handler, array $middlewares=[]): void {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put(string $path, callable|array $handler, array $middlewares=[]): void {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function delete(string $path, callable|array $handler, array $middlewares=[]): void {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    // implement reflection class
    public function resource(string $basePath, string $controller, array $middlewares = []) {
        $ref = new \ReflectionClass($controller);

        // mapping HTTP + Path
        $routes = [
            'index' => ['GET', $basePath],
            'show' => ['GET', $basePath.'/{id}'],
            'store' => ['POST', $basePath],
            'update' => ['PUT', $basePath.'/{id}'],
            'destroy' => ['DELETE', $basePath.'/{id}'],
            'search' => ['GET', $basePath.'/search'],
        ];

        foreach ($routes as $method => [$httpVerb, $path]) {
            if ($ref->hasMethod($method)) {
                $this->{strtolower($httpVerb)}($path, [$controller, $method], $middlewares);
            }
        }
    }

    // implement trycatch exception
    public function resolve(Request $request, Response $response) {
        try {
            $method = $request->getMethod();
            $uri = $request->getUri();

            foreach ($this->routes[$method] ?? [] as $path => $route) {
                $pattern = "#^" . preg_replace('#\{([^/]+)\}#', '([^/]+)', $path) . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); 
                    
                    $handler = $this->buildHandler($route['handler'], $matches);
                    $pipeline = $this->applyMiddlewares($route['middlewares'], $handler);

                    return $pipeline($request, $response);
                };
            }
        } catch (\Throwable $e) {
            ExceptionHandler::handle($e);
        }
    }

    // solve resource dependency injection
    private function buildHandler(callable|array $handler, array $params): callable {
       return function($req, $res) use ($handler, $params){
            if (is_array($handler)) {
                [$ctrl, $method] = $handler;
                $ctrl = $this->container->get($ctrl);
                return call_user_func_array([$ctrl,$method], array_merge([$req, $res], $params));
            }
        };
    }

    private function applyMiddlewares(array $middlewares, callable $handler): callable {
        return array_reduce(
            array_reverse($middlewares),
                fn($next, $m) => function($req, $res) use ($m, $next) {
                $instance = new $m();
                return $instance->handle($req, $res, $next);
            },
            $handler
        );
    }
}

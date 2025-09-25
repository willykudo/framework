<?php

namespace WillyFramework\src\Core;

use WillyFramework\src\Router\Router;

class App {
    private Router $router;
    private Request $request;
    private Response $response;
    private Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($container);
    }

    public function getRouter(): Router {
        return $this->router;
    }

    public function getContainer(): Container {
        return $this->container;
    }

    public function getDb() : \PDO {
        return Database::getInstance()->getConnection();
    }

    public function run(): void {
        $this->router->resolve($this->request, $this->response);
    }
}

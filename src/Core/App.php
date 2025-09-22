<?php

namespace WillyFramework\src\Core;


use WillyFramework\pkg\ExceptionHandler;
use WillyFramework\src\Router\Router;

class App {
    private Router $router;
    private Request $request;
    private Response $response;

    public function __construct() {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();

        set_exception_handler([ExceptionHandler::class, 'handle']);
    }

    public function getRouter(): Router {
        return $this->router;
    }

    public function getDb() : \PDO {
        return Database::getInstance()->getConnection();
    }

    public function run(): void {
        $this->router->resolve($this->request, $this->response);
    }
}

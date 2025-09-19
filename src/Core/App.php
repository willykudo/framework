<?php

namespace WillyFramework\src\Core;

use WillyFramework\config\Config;
use WillyFramework\pkg\ExceptionHandler;
use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;
use WillyFramework\src\Router\Router;

class App {
    private Router $router;
    private Request $request;
    private Response $response;
    private Database $db;

    public function __construct(string $envPath = __DIR__.'/../../.env') {
        Config::load($envPath);

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();

        $this->db = Database::getInstance();

        set_exception_handler([ExceptionHandler::class, 'handle']);
    }

    public function getRouter(): Router {
        return $this->router;
    }

    public function getDb(): Database {
        return $this->db;
    }

    public function run(): void {
        $this->router->resolve($this->request, $this->response);
    }
}

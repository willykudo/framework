<?php

namespace WillyFramework\src\Middleware;

use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;
use WillyFramework\config\Config;

class AuthMiddleware {
    private string $token;

    public function __construct() {
        $this->token = Config::get('TOKEN');
    }

    public function handle (Request $request, Response $response, callable $next) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'];

        if ($authHeader !== "Bearer {$this->token}"){
            $response->setStatus(401)->json([
                'error' => 'Unauthorized',
            ]);
            return;
        }

        return $next($request, $response);
    }
}
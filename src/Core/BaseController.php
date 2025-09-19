<?php 

namespace WillyFramework\src\Core;

use WillyFramework\src\Core\Response;

class BaseController {

    protected function jsonResponse(Response $res, array $data, int $statusCode = 200): Response {
        return $res->setStatus($statusCode)->json($data);
    }
    
}
<?php 

use WillyFramework\src\Core\Container;
use WillyFramework\src\Core\Database;
use WillyFramework\src\Repository\UserRepository;
use WillyFramework\src\Controllers\UserController;

$container = new Container();

$container->set(\PDO::class, function() {
    return Database::getInstance()->getConnection();
});

$container->set(UserRepository::class, function($c) {
    return new UserRepository($c->get(\PDO::class));
});

$container->set(UserController::class, function($c) {
    return new UserController($c->get(UserRepository::class));
});

return $container;
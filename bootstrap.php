<?php

require __DIR__.'/vendor/autoload.php';

use WillyFramework\config\Config;
use WillyFramework\pkg\ExceptionHandler;
use WillyFramework\src\Core\App;

set_exception_handler([ExceptionHandler::class, 'handle']);

Config::load(__DIR__.'/.env');

$container = require __DIR__.'/config/Services.php';

return new App($container);
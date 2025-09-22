<?php

require __DIR__.'/vendor/autoload.php';

use WillyFramework\config\Config;
use WillyFramework\src\Core\Database;

Config::load(__DIR__.'/.env');
return Database::getInstance()->getConnection();
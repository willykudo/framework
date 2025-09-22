<?php

require __DIR__.'/vendor/autoload.php';

use WillyFramework\config\Config;
use WillyFramework\src\Core\App;

Config::load(__DIR__.'/.env');

return new App();
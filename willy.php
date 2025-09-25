#!/usr/bin/env php
<?php

require __DIR__ . '/bootstrap.php';

use WillyFramework\src\Console\Kernel;

$kernel = new Kernel();

$command = $argv[1];
$kernel->handle($command);
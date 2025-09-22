<?php 

namespace WillyFramework\src\Console\Commands;

use WillyFramework\config\Config;

class ServeCommand {
    public string $description = "Run PHP built-in server";

    public function execute(): void {
        $host = Config::get('DB_HOST');
        $port = Config::get('PORT');

        echo "Starting server at http://$host:$port\n";
        passthru("php -S $host:$port -t src");
    }
}
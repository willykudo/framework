<?php 

namespace WillyFramework\src\Console;

use WillyFramework\src\Console\Commands\SeederCommand;
use WillyFramework\src\Console\Commands\MigrateCommand;
use WillyFramework\src\Console\Commands\ServeCommand;

class Kernel {
    protected array $commands = [];

    public function __construct() {
        $this->commands = [
            'migrate' => new MigrateCommand(),
            'seed' => new SeederCommand(),
            'serve' => new ServeCommand(),
        ];
    }

    public function handle(?string $command): void {
        if (!$command || !isset($this->commands[$command])){
            $this->printHelp();
            return;
        }

        $this->commands[$command]->execute();
    }

    private function printHelp(): void {
        echo "Willy CLI \n";
        echo "Usege: \n";
        foreach ($this->commands as $name => $cmd) {
            echo " willy {$name }  {$cmd->description} \n";
        }
        echo "\n";
    }
}
<?php 

namespace WillyFramework\src\Core;

class Container {
    private array $bindings = [];

    public function set(string $abstract, callable $concrete):void {
        $this->bindings[$abstract] = $concrete;
    }

    public function get(string $abstract) {
        if (!isset($this->bindings[$abstract])) {
            throw new \Exception("No binding found for {$abstract}");
        }

        return $this->bindings[$abstract]($this);
    }
}
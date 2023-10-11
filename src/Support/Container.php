<?php 

namespace App\Support;

class Container {
    protected $receipts = []; 
    protected $instances = [];

    public function add(string $key, \Closure $func) {
        $this->receipts[$key] = $func;
    }

    public function get($what) {
        if(!isset($this->instances[$what])) {
            $this->instances[$what] = $this->receipts[$what]();
        }
        return $this->instances[$what];
    }
}
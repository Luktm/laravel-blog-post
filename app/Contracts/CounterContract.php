<?php

namespace App\Contracts;

// contract is like abstract class and implement it in Services\Counter.php
interface CounterContract
{
    public function increment(string $key, array $tags = null): int;
}

?>

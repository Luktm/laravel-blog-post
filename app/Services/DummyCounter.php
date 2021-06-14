<?php
namespace App\Services;

use App\Contracts\CounterContainer;
use App\Contracts\CounterContract;

// this is an example how we can easily switch the contract in AppServiceProvider.php without modify a lot of code in your project
class DummyCounter implements CounterContract // remember bind it in AppServiceProvider.php
{
    public function increment(string $key, array $tags = null): int {
        dd("I'm a dummy counter not implemented yet!");
        return 0;
    }
}
?>

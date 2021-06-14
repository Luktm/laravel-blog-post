<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * A Facade do contract
 * Facade bascialy the static accessor
 * to use implement in Contract,
 * * remember to use implement in Contracts must register in AppServiceProvider.php with bind() method
 * @method static int increment(string $key, array $tags = null)
 */

class CounterFacade extends Facade
{
    public static function getFacadeAccessor()
    /**
     * place contract here
     */
    {
        return 'App\Contracts\CounterContract';
    }
}
?>

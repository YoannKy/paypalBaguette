<?php 

namespace Yoannky\Paypalbaguette\Facades;

use Illuminate\Support\Facades\Facade;

class PaypalBaguette extends Facade {
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paypalbaguette'; 
    }
}
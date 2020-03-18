<?php

namespace Yashiroiori\AclManagerTabler\Facades;

use Illuminate\Support\Facades\Facade;

class AclManagerTabler extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aclmanagertabler';
    }
}

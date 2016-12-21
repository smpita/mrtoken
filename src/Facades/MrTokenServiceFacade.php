<?php

namespace Hackage\MrToken\Facades;

use Illuminate\Support\Facades\Facade;
use Hackage\MrToken\MrTokenService;

class MrTokenServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MrTokenService::class;
    }
}

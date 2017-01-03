<?php

namespace Hackage\MrToken\Traits;

trait MrTokenTrait
{
    public function freshApiToken()
    {
        $tokenUserModel = config('mrtoken.USER_MODEL', 'App\User');
        if($this instanceof $tokenUserModel)
        {
            return MrToken::generate($this);
        }
        return false;
    }
}

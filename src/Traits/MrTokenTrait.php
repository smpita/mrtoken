<?php

namespace Hackage\MrToken\Traits;

use Hackage\MrToken\MrTokenService;

trait MrTokenTrait
{
    /**
     * Generate a new token for the user.
     * @return mixed string|boolean
     */
    public function freshApiToken()
    {
        $tokenUserModel = config('mrtoken.USER_MODEL', 'App\User');
        if($this instanceof $tokenUserModel)
        {
            return (new MrTokenService)->generate($this);
        }
        return false;
    }

    /**
     * Regenerate the last token for the user.
     * @return mixed string|boolean
     */
    public function lastApiToken()
    {
        $tokenUserModel = config('mrtoken.USER_MODEL', 'App\User');
        if($this instanceof $tokenUserModel)
        {
            return (new MrTokenService)->regenerate($this);
        }
        return false;
    }



}

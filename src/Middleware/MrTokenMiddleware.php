<?php

namespace Hackage\MrToken\Middleware;

use Hackage\MrToken\Facades\MrTokenServiceFacade;
use Illuminate\Support\Facades\Auth;
use Closure;

class MrTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->wantsJson())
        {
            $user = MrTokenServiceFacade::resolve($request->header(config('mrtoken.TOKEN_HEADER', 'mrtoken')));
            $tokenUserModel = config('mrtoken.USER_MODEL', 'App\User');
            if($user instanceof $tokenUserModel)
            {
                Auth::guard($guard)->onceUsingId($user->id);
                return $next($request);
            }
        };
        return $next($request);
    }
}

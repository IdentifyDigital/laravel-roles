<?php

namespace IdentifyDigital\Roles\Http\Middleware;

use Closure;
use IdentifyDigital\Roles\Exceptions\Middleware\AccessDeniedException;
use Illuminate\Http\Request;

class VerifyHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param array $roles
     * @return void
     *
     * @throws AccessDeniedException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if($this->validate($request, $roles))
            return $next($request);

        throw new AccessDeniedException(
            'Unauthenticated.', [], $this->redirectTo($request)
        );
    }

    /**
     * Validates the given user has one of the specified roles.
     *
     * @param Request $request
     * @param array $roles
     * @return bool
     */
    protected function validate(Request $request, array $roles)
    {
        $user = $request->user();

        if(!method_exists($user, 'hasRole'))
           return false;

        foreach($roles as $role) {
            if($user->hasRole($role))
                return true;
        }

        return false;
    }

    /**
     * Get the path the user should be redirected to when they are not assigned to any of the roles.
     *
     * @param Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        return route('login');
    }
}

<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('getImpersonatedUser')) {
    function getImpersonatedUser()
    {
        if (Auth::user()->isImpersonated()) {
            return Auth::user()->getImpersonator();
        }
    }
}

if (! function_exists('getUserId')) {
    function getUserId()
    {
        return Auth::id();
    }
}

if (! function_exists('getRole')) {
    function getRole()
    {
        return Auth::user()->getRoleNames()->first();
    }
}

if (! function_exists('checkRole')) {
    /**
     * Check if user has the specified role
     *
     * @param  string  ...$role  Role name
     * @return bool True if user has the role, false otherwise
     */
    function checkRole(string ...$role)
    {
        return in_array(getRole(), $role);
    }
}

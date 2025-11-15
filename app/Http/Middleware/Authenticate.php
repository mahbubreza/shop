<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when not authenticated.
     */
    protected function redirectTo($request)
    {
        // Only redirect if it's not an AJAX/fetch request
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}

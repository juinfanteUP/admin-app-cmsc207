<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAnonymous
{
    public function handle(Request $req, Closure $next)
    {
        if(!Session()->has('user'))
        {
            return redirect('login');
        }

        return $next($req);
    }
}

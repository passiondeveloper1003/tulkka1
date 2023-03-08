<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PanelAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->check() and !auth()->user()->isAdmin()) {

            $referralSettings = getReferralSettings();
            view()->share('referralSettings', $referralSettings);

            return $next($request);
        }

        return redirect('/login');
    }
}

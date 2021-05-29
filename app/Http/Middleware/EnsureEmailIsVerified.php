<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() || ($request->user() instanceof MustVerifyEmail && !$request->user()->hasVerifiedEmail())) {
            if($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => '',
                        'title' => 'Your email address is not verified',
                        'status' => 403,
                        'detail' => 'Your email address is not verified',
                        'instance' => ''
                    ]
                ], 403);
            } else {
                return redirect(route('verification.notice'));
            }
        }

        return $next($request);
    }
}

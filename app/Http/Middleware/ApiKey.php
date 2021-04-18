<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-Api-Key') != env('APP_KEY')) {
            return response()->json([
                'success'=> false,
                'errors' => [
                    'api_key'=> 'Invalid API key or missing required header. You can also try to generate a new API key in the Website Toolbox admin area in case your API key is not configured to work with the REST API.'
                ]
            ], 400);
        }
        return $next($request);
    }
}

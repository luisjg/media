<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('secret_key') && $request->get('secret_key') === env('SECRET_KEY')) {
            return $next($request);
        } else {
            return response()->json([
                'success' => 'false',
                'status' => '404',
                'api' => 'media',
                'version' => '1.0',
                'message' => 'Please check your API key.'
            ]);
        }
    }
}

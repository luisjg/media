<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Input;

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
        $str = $request->url();

        //if type is not student, it gets the next request
        if (strpos($str, 'student') == false) {
            return $next($request);
        } else if (!empty($request->get('secret')) && ($request->get('secret') === env('SECRET_KEY'))) {
            return $next($request);
        } else if (env('SECRET_KEY') === $request->header('X-API-Key')) {
            return $next($request);
        } else {
            return response()->json([
                'success' => 'false',
                'status' => '404',
                'api' => 'media',
                'version' => '1.0',
                'message' => ['Please check your API key.']
            ]);
        }
    }
}

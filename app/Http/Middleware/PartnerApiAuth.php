<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;

class PartnerApiAuth
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

        $user = auth()->guard('partner-api')->user();

        if ($user && !$user->status == 2)
            return [
                'status' => 'failed',
                'action' => 'log_out',
                'display_message' => 'This account has been suspended',
                'data' => []
            ];

        $request->merge(compact('user'));
        return $next($request);

    }
}

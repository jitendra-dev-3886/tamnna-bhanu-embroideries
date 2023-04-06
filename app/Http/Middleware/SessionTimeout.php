<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Auth;

class SessionTimeout
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
        // If user is not logged in...
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::guard()->user();

        $now = Carbon::now();

        $last_seen = Carbon::parse($user->last_seen_at);

        $absence = $now->diffInSeconds($last_seen);

        // If user has been inactivity longer than the allowed inactivity period
        if ($absence > config('session.lifetime')) {

            $request->user()->token()->revoke();

            return \Illuminate\Support\Facades\Response::make("Authorization Token not found", config('constants.validation_codes.unauthorized'));
        }

        $user->last_seen_at = $now->format('Y-m-d H:i:s');
        $user->save();

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsSeller
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
        if (Auth::check() && Auth::user()->user_type == 'seller'  && !Auth::user()->banned  ) {
            $user = Auth::user();

            if (Auth::user()->status != "Enabled" || (Auth::user()->status == "Enabled"  && Auth::user()->id != Auth::user()->owner_id  && Auth::user()->step_number != 1 )) {
                // Redirect to shops.create with the step number
                return redirect()->route('shops.create', ['step' => $user->step_number]);
            }
            return $next($request);
        }
        else{
            abort(404);
        }
    }
}

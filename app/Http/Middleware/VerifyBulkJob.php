<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyBulkJob
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentJob = session('bulk_job', null);
        $requestJobId = $request->header('job-id');
    
        if (!$currentJob || $currentJob['id'] !== $requestJobId) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired job session'
            ], 401);
        }
    
        return $next($request);
    }
}

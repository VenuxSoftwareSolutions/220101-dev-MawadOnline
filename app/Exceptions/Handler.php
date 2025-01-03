<?php

namespace App\Exceptions;

use App\Utility\NgeniusUtility;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // Log the exception
        \Log::error('Exception occurred: ' . $e->getMessage(), [
            'exception' => $e,
            'request' => $request->all(),
        ]);
    
        // Handle ThrottleRequestsException (rate-limiting)
        if ($e instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }
    
        // Handle custom logic for specific routes
        if ($this->isHttpException($e)) {
            if ($request->is('customer-products/admin')) {
                return NgeniusUtility::initPayment();
            }
        }
    
        // Fallback to default exception handling
        return parent::render($request, $e);
    }
}
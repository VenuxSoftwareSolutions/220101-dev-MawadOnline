<?php

namespace App\Exceptions;

use App\Utility\NgeniusUtility;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        // Log the exception with additional request details
        \Log::channel('custom_exception_log')->error('Exception occurred', [
            'url' => request()->fullUrl(), // Log the full URL of the request
            'method' => request()->method(), // Log the HTTP method of the request
            'payload' => request()->all(), // Log the request payload/body
            'user_id' => auth()->check() ? auth()->id() : 'Guest', // Log the authenticated user's ID, or 'Guest' if not authenticated
            'exception' => [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ] // Logging basic exception details for context
        ]);

        // Check if the exception is an instance of ErrorException
        if ($exception instanceof \ErrorException) {
            // Define your standard error message and status code
            $standardMessage = 'An error occurred. Please try again later.';
            $statusCode = 500; // Internal Server Error

            // Check if the request expects a JSON response (e.g., for API endpoints)
            if ($request->expectsJson()) {
                return response()->json(['error' => $standardMessage], $statusCode);
            }

            // For web requests, you might want to redirect or render a specific view
            // return response()->view('errors.generic_error', ['error' => $standardMessage], $statusCode);
            // Or simply return a plain error message
            return response()->view('errors.custom_error', ['error' => $standardMessage], $statusCode);
        }

        // Handle other exceptions as per Laravel's default mechanism
        return parent::render($request, $exception);

//        if ($this->isHttpException($e)) {
//            if ($request->is('customer-products/admin')) {
//                return NgeniusUtility::initPayment();
//            }
//
//            return parent::render($request, $e);
//        } else {
//            return parent::render($request, $e);
//        }
    }
}

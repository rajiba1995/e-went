<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Handle unauthenticated exceptions.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Ensure API requests return a JSON response with a 403 status
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        // For web requests, redirect to the admin login page
        return redirect()->route('admin.login');
    }
}


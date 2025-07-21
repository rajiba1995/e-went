<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUnderMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        // You can toggle this from .env or database
        if (config('app.admin_maintenance')) {
            return response()->view('errors.maintenance'); // Blade view for maintenance page
        }

        return $next($request);
    }
}

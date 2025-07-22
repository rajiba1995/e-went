<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs that should be excluded from CSRF verification.
     */
     protected $except = [
        'api/customer/esign/thankyou', // ← Add this line
        'api/customer/digilocker/aadhar/thankyou', // ← Add this line
        'api/customer/icici/thankyou', // ← Add this line
        'internal-admin-login',
        'api/customer/payment/ipn',
    ];
}

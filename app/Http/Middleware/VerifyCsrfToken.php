<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
       'http://127.0.0.1:8000/customers/register',
       'http://127.0.0.1:8000/customers/verify-otp',
    // 'http://127.0.0.1:8000/customers/45/profiles',
       'http://127.0.0.1:8000/customers/profiles',
       'http://127.0.0.1:8000/customers/interests'
    ];
}

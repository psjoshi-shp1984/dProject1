<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignature
{
    /**
     * The names of the query parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $ignore = [
        // e.g. 'fbclid', 'utm_source'
    ];
}

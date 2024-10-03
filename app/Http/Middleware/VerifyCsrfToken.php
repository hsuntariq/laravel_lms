<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/dashboard/student/upload-assignment',  // Add the specific route here
        '/dashboard/teacher/mark-assignment',
        '/dashboard/staff/batches/add-batches',
    ];
}

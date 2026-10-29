<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

/**
 * @deprecated Laravel 13 mengganti VerifyCsrfToken dengan PreventRequestForgery.
 *             Class ini dipertahankan sebagai alias agar referensi lama tetap valid.
 */
class VerifyCsrfToken extends PreventRequestForgery
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}

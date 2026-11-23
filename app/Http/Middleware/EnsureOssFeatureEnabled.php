<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Settings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menahan akses Operator ke Layanan OSS ketika saklar fitur
 * (settings.oss_feature_enabled) dimatikan dari halaman Konfigurasi admin.
 *
 * Permintaan AJAX (mis. upload pada form OSS) dibalas JSON, sedangkan
 * permintaan halaman biasa diarahkan ke halaman maintenance.
 */
class EnsureOssFeatureEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Settings::featureEnabled('oss_feature_enabled')) {
            return $next($request);
        }

        $pesan = 'Layanan OSS sedang maintenance, layanan dialihkan ke Tim OSS PBNU melalui PCNU masing-masing.';

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'maintenance',
                'message' => $pesan,
            ], 503);
        }

        return redirect()->route('oss.maintenance');
    }
}

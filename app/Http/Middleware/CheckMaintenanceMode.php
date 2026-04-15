<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Cookie name used to bypass maintenance mode for logged-in admins.
     */
    public const BYPASS_COOKIE = 'maintenance_bypass';
    public const BYPASS_SECRET = 'cmic-admin-bypass-2026';

    public function handle(Request $request, Closure $next): Response
    {
        // Always allow admin routes through
        if ($request->is('admin/*') || $request->is('admin')) {
            return $next($request);
        }

        // Keep framework health check endpoint accessible
        if ($request->is('up')) {
            return $next($request);
        }

        // Allow access if admin bypass cookie is present and valid
        $bypassToken = $request->cookie(self::BYPASS_COOKIE);
        if ($bypassToken && $bypassToken === hash('sha256', self::BYPASS_SECRET)) {
            return $next($request);
        }

        try {
            $isMaintenance = Setting::get('maintenance_mode', '0') === '1';
        } catch (\Exception $e) {
            return $next($request);
        }

        if ($isMaintenance) {
            // Allow access from allowed IPs
            $allowedIps = array_filter(
                explode(',', Setting::get('maintenance_allowed_ips', '')),
                fn($ip) => trim($ip) !== ''
            );
            if (!empty($allowedIps) && in_array($request->ip(), array_map('trim', $allowedIps))) {
                return $next($request);
            }

            return response()->view('maintenance', [
                'title'   => Setting::get('maintenance_title', 'Sedang Dalam Pemeliharaan'),
                'message' => Setting::get('maintenance_message', 'Website sedang dalam pemeliharaan. Silakan kunjungi kembali beberapa saat lagi.'),
            ], 503);
        }

        return $next($request);
    }

    /**
     * Generate the bypass cookie to set on admin login.
     */
    public static function makeBypassCookie()
    {
        return cookie(
            self::BYPASS_COOKIE,
            hash('sha256', self::BYPASS_SECRET),
            60 * 24 * 7, // 7 days
            '/',
            null,
            false,
            true // httpOnly
        );
    }
}


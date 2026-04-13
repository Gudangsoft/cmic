<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
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
}

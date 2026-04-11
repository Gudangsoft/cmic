<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogAdminActivity
{
    // Map HTTP method + route name suffix → action label
    private const METHOD_ACTION = [
        'POST'   => 'create',
        'PUT'    => 'update',
        'PATCH'  => 'update',
        'DELETE' => 'delete',
    ];

    // Routes that should NOT be logged
    private const SKIP_ROUTES = [
        'admin.login',
        'admin.login.post',
        'admin.logout',
        'admin.activity-logs.index',
        'admin.activity-logs.clear',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log mutating methods from authenticated users
        if (! auth()->check()) {
            return $response;
        }

        $method    = $request->method();
        $routeName = $request->route()?->getName() ?? '';

        // Log login/logout separately via AuthController, skip here
        if (in_array($routeName, self::SKIP_ROUTES, true)) {
            return $response;
        }

        if (! isset(self::METHOD_ACTION[$method])) {
            return $response;
        }

        // Only log successful responses (redirect = success after mutation)
        $status = $response->getStatusCode();
        if ($status >= 400) {
            return $response;
        }

        $action      = self::METHOD_ACTION[$method];
        $description = $this->buildDescription($action, $routeName, $request);

        ActivityLog::log($action, $description);

        return $response;
    }

    private function buildDescription(string $action, string $routeName, Request $request): string
    {
        // Extract resource name from route e.g. "admin.projects.store" → "projects"
        $parts    = explode('.', $routeName);
        $resource = $parts[1] ?? $routeName;

        $labels = [
            'sliders'   => 'Slider',
            'services'  => 'Layanan',
            'team'      => 'SDM / Tim',
            'projects'  => 'Proyek',
            'clients'   => 'Klien',
            'galleries' => 'Galeri',
            'menus'     => 'Menu',
            'pages'     => 'Halaman',
            'contacts'  => 'Kontak',
            'settings'  => 'Pengaturan',
            'about'     => 'Halaman Tentang',
        ];

        $resourceLabel = $labels[$resource] ?? ucfirst($resource);

        $actionLabels = [
            'create' => 'Menambah',
            'update' => 'Mengubah',
            'delete' => 'Menghapus',
        ];

        $actionLabel = $actionLabels[$action] ?? $action;

        // Try to include a name from request
        $name = $request->input('name') ?? $request->input('title') ?? '';
        $suffix = $name ? " \"{$name}\"" : '';

        return "{$actionLabel} data {$resourceLabel}{$suffix}";
    }
}

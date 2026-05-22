<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isViewer()) {
            return redirect()->route('admin.dashboard')
                ->with('viewer_blocked', 'Akses hanya tersedia untuk Administrator.');
        }

        return $next($request);
    }
}

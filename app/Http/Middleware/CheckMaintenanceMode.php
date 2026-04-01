<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin*') || $request->is('login') || $request->is('register') || $request->is('forgot-password')) {
            return $next($request);
        }

        if ($request->user()?->is_admin ?? false) {
            return $next($request);
        }

        if (! Schema::hasTable('settings')) {
            return $next($request);
        }

        if (Setting::get('maintenance_mode') !== '1') {
            return $next($request);
        }

        return response()->view('maintenance', [
            'siteName' => Setting::get('site_name') ?: config('app.name'),
        ], 503);
    }
}

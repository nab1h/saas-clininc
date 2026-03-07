<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super Admin has access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Get current clinic from session
        $currentClinicId = session('current_clinic_id');

        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')
                ->with('error', 'يجب اختيار عيادة أولاً.');
        }

        // Check if user has access to the current clinic
        if (!$user->hasAccessToClinic($currentClinicId)) {
            return redirect()->route('dashboard.index')
                ->with('error', 'غير مصرح لك بالوصول لهذه العيادة.');
        }

        // Check if user has any of the required roles in the current clinic
        $userRole = $user->getRoleInClinic($currentClinicId);

        if (!$userRole || !in_array($userRole->slug, $roles)) {
            abort(403, 'ليس لديك الصلاحية للوصول لهذه الصفحة.');
        }

        return $next($request);
    }
}

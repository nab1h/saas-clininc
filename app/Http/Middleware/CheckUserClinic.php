<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserClinic
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for login and register pages
        if (in_array($request->path(), ['login', 'register', '/'])) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Share user with all views
        view()->share('user', $user);

        // Get clinics user can access
        $userClinics = $user->accessibleClinics();

        // Share user clinics with all views
        view()->share('userClinics', $userClinics);

        // Super Admin can access all clinics
        if ($user->isSuperAdmin()) {
            $currentClinicId = session('current_clinic_id');

            // If no clinic selected, use first one
            if (!$currentClinicId && $userClinics->count() > 0) {
                $firstClinic = $userClinics->first();
                $currentClinicId = $firstClinic->id;
                session()->put('current_clinic_id', $currentClinicId);
            }

            // Share current clinic with all views
            if ($currentClinicId) {
                $currentClinic = \App\Models\Clinic::find($currentClinicId);
                if ($currentClinic) {
                    view()->share('currentClinic', $currentClinic);
                    view()->share('currentClinicId', $currentClinicId);
                }
            }

            return $next($request);
        }

        // Regular users with assigned clinics
        if ($userClinics->count() === 0) {
            return redirect()->route('dashboard.index')
                ->with('error', 'لا توجد عيادات مرتبطة بحسابك.');
        }

        // Get current clinic from session
        $currentClinicId = session('current_clinic_id');

        // Validate that current clinic belongs to the user
        if ($currentClinicId && !$userClinics->contains('id', $currentClinicId)) {
            session()->forget('current_clinic_id');
            $currentClinicId = null;
        }

        // If no current clinic, use default one or first one
        if (!$currentClinicId) {
            $defaultClinic = $userClinics->where('pivot.is_default', true)->first();
            $currentClinicId = $defaultClinic ? $defaultClinic->id : $userClinics->first()->id;
            session()->put('current_clinic_id', $currentClinicId);
        }

        // Share current clinic with all views
        if ($currentClinicId) {
            $currentClinic = \App\Models\Clinic::find($currentClinicId);
            if ($currentClinic) {
                view()->share('currentClinic', $currentClinic);
                view()->share('currentClinicId', $currentClinicId);
            }
        }

        return $next($request);
    }
}

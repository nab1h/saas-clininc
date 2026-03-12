<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Login attempt', ['email' => $request->email]);

        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            \Log::info('Login validation passed');

            if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                $request->session()->regenerate();

                // Get the current user
                $user = Auth::user();

                // Check if user is approved
                if (!$user->is_approved) {
                    Auth::logout();
                    return redirect('/login')->with('error', 'حسابك قيد المراجعة من قبل الإدارة. يرجى انتظار موافقة الدخول.');
                }

                // Get user's clinics with pivot data
                $userClinics = $user->clinics()->withPivot(['role_id'])->get();

                // Find first active clinic or one marked as default
                $firstClinic = null;
                foreach ($userClinics as $clinic) {
                    if ($clinic->is_active && $clinic->pivot->is_default) {
                        $firstClinic = $clinic;
                        break;
                    }
                }

                // If no default clinic, get first active one
                if (!$firstClinic) {
                    $firstClinic = $userClinics->where('is_active', true)->first();
                }

                // Set current clinic in session
                if ($firstClinic) {
                    session()->put('current_clinic_id', $firstClinic->id);
                }

                \Log::info('Login successful', ['email' => $request->email, 'clinic_id' => $firstClinic ? $firstClinic->id : null]);
                return redirect()->intended('/dashboard');
            }

            \Log::info('Login failed', ['email' => $request->email]);
            return back()->with('error', 'بيانات الاعتماد المقدمة غير صحيحة.')->onlyInput('email');
        } catch (\Exception $e) {
            \Log::error('Login error', ['message' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح!');
    }
}

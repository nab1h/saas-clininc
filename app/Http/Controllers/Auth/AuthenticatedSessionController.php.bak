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

                \Log::info('Login successful', ['email' => $request->email]);

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

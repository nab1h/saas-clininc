<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Register attempt', $request->all());

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            \Log::info('Validation passed', $validated);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_approved' => false,
            ]);

            \Log::info('User created', ['user_id' => $user->id]);

            event(new Registered($user));

            // Don't login the user automatically
            // Auth::login($user);

            if ($request->expectsJson()) {
                return response()->json(['redirect' => '/pending-approval']);
            }

            return redirect('/pending-approval')->with('success', 'تم إنشاء الحساب بنجاح! يرجى انتظار موافقة الإدارة.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            if ($request->expectsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Registration error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage())->withInput();
        }
    }
}

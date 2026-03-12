<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('clinics')->latest()->get();
        $roles = Role::all();
        return view('dashboard.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('dashboard.users.form', compact('user', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'clinic_id' => ['nullable', 'exists:clinics,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Assign to clinic if provided
        if (!empty($validated['clinic_id']) && !empty($validated['role_id'])) {
            $clinic = Clinic::find($validated['clinic_id']);
            $clinic->users()->attach($user->id, [
                'role_id' => $validated['role_id'],
                'is_default' => false,
            ]);
        }

        return redirect()->route('dashboard.users.index')->with('success', 'تمت إضافة المستخدم.');
    }

    public function edit(User $user): View
    {
        $user->load('clinics');
        $roles = Role::all();
        return view('dashboard.users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'clinic_id' => ['nullable', 'exists:clinics,id'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('dashboard.users.index')->with('success', 'تم تحديث المستخدم.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->clinics()->detach();
        $user->delete();
        return redirect()->route('dashboard.users.index')->with('success', 'تم حذف المستخدم.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        // You can add an 'is_active' field to users table for this
        return redirect()->route('dashboard.users.index')->with('success', 'تم تحديث حالة المستخدم.');
    }

    public function assignClinic(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'clinic_id' => ['required', 'exists:clinics,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $clinic = Clinic::find($validated['clinic_id']);

        // Check if user is already assigned to this clinic
        if ($clinic->users()->where('user_id', $user->id)->exists()) {
            $clinic->users()->updateExistingPivot($user->id, [
                'role_id' => $validated['role_id'],
            ]);
        } else {
            $clinic->users()->attach($user->id, [
                'role_id' => $validated['role_id'],
                'is_default' => false,
            ]);
        }

        return redirect()->route('dashboard.users.index')->with('success', 'تم تعيين المستخدم للعيادة.');
    }

    public function removeClinic(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'clinic_id' => ['required', 'exists:clinics,id'],
        ]);

        $clinic = Clinic::find($validated['clinic_id']);
        $clinic->users()->detach($user->id);

        return redirect()->route('dashboard.users.index')->with('success', 'تم إزالة المستخدم من العيادة.');
    }

    public function approveUser(User $user): RedirectResponse
    {
        $user->update(['is_approved' => true]);

        return redirect()->route('dashboard.users.index')->with('success', 'تم قبول المستخدم بنجاح.');
    }

    public function rejectUser(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('dashboard.users.index')->with('success', 'تم رفض وحذف المستخدم.');
    }
}

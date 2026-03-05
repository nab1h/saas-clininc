<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClinicController extends Controller
{
    public function index(): View
    {
        $clinics = Clinic::with('users', 'branches')->latest()->get();
        $roles = Role::all();
        $users = User::all();
        return view('dashboard.clinics.index', compact('clinics', 'roles', 'users'));
    }

    public function create(): View
    {
        $users = User::all();
        $roles = Role::all();
        return view('dashboard.clinics.form', [
            'users' => $users,
            'roles' => $roles,
            'clinic' => null
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'subscription_plan' => ['nullable', 'string', 'max:50'],
            'trial_ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'manager_role_id' => ['nullable', 'exists:roles,id'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'subscription_plan' => $validated['subscription_plan'] ?? 'basic',
            'trial_ends_at' => $validated['trial_ends_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('clinics', 'public');
        }

        $clinic = Clinic::create($data);

        // Assign manager if provided
        if (!empty($validated['manager_id']) && !empty($validated['manager_role_id'])) {
            $clinic->users()->attach($validated['manager_id'], [
                'role_id' => $validated['manager_role_id'],
                'is_default' => false,
            ]);
        }

        return redirect()->route('dashboard.clinics.index')->with('success', 'تمت إضافة العيادة.');
    }

    public function edit(Clinic $clinic): View
    {
        $clinic->load('users', 'users.roles');
        $users = User::all();
        $roles = Role::all();
        return view('dashboard.clinics.form', compact('clinic', 'users', 'roles'));
    }

    public function update(Request $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'subscription_plan' => ['nullable', 'string', 'max:50'],
            'trial_ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'subscription_plan' => $validated['subscription_plan'] ?? 'basic',
            'trial_ends_at' => $validated['trial_ends_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('logo')) {
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            $data['logo'] = $request->file('logo')->store('clinics', 'public');
        }

        $clinic->update($data);

        return redirect()->route('dashboard.clinics.index')->with('success', 'تم تحديث العيادة.');
    }

    public function destroy(Clinic $clinic): RedirectResponse
    {
        if ($clinic->logo) {
            Storage::disk('public')->delete($clinic->logo);
        }

        // Detach all users
        $clinic->users()->detach();

        $clinic->delete();
        return redirect()->route('dashboard.clinics.index')->with('success', 'تم حذف العيادة.');
    }

    public function toggleStatus(Clinic $clinic): RedirectResponse
    {
        $clinic->update(['is_active' => !$clinic->is_active]);
        $status = $clinic->is_active ? 'تفعيل' : 'تعطيل';
        return redirect()->route('dashboard.clinics.index')->with('success', "تم {$status} العيادة.");
    }

    public function assignManager(Request $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Check if user is already assigned to this clinic
        $existingPivot = $clinic->users()->where('user_id', $validated['user_id'])->first();

        if ($existingPivot) {
            // Update role if exists
            $clinic->users()->updateExistingPivot($validated['user_id'], [
                'role_id' => $validated['role_id'],
            ]);
        } else {
            // Attach new user with role
            $clinic->users()->attach($validated['user_id'], [
                'role_id' => $validated['role_id'],
                'is_default' => false,
            ]);
        }

        return redirect()->route('dashboard.clinics.index')->with('success', 'تم تعيين المسؤول للعيادة.');
    }

    public function removeUser(Request $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $clinic->users()->detach($validated['user_id']);

        return redirect()->route('dashboard.clinics.index')->with('success', 'تم إزالة المستخدم من العيادة.');
    }
}

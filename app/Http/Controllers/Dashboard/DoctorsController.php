<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorsController extends Controller
{
    public function index(): View
    {
        // Get current clinic from session (set by CheckUserClinic middleware)
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (!$clinic) {
            return redirect()->route('dashboard.index')->with('error', 'العيادة غير موجودة.');
        }

        // Show doctors ONLY for current logged-in clinic (not all clinics)
        $doctors = Doctor::where('clinic_id', $currentClinicId)
            ->orderBy('name')
            ->orderBy('id')
            ->get();

        return view('dashboard.doctors.index', compact('doctors'));
    }

    public function create(): View|RedirectResponse
    {
        // Get current clinic from session (set by CheckUserClinic middleware)
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (! $clinic) {
            return redirect()->route('dashboard.doctors.index')->with('error', 'العيادة غير موجودة.');
        }
        return view('dashboard.doctors.form', ['doctor' => null, 'clinic' => $clinic]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Get current clinic from session (set by CheckUserClinic middleware)
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (! $clinic) {
            return redirect()->route('dashboard.doctors.index')->with('error', 'العيادة غير موجودة.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $doctor = Doctor::create([
            'clinic_id' => $clinic->id,
            'name' => $validated['name'],
            'job' => $validated['job'],
            'description' => $validated['description'] ?? null,
        ]);

        // Handle image upload if present
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('doctors', 'public');
            $doctor->update(['image' => $path]);
        }

        return redirect()->route('dashboard.doctors.index')->with('success', 'تمت إضافة الطبيب.');
    }

    public function edit(Doctor $doctor): View
    {
        // Verify doctor belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $doctor->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.doctors.index')->with('error', 'غير مصرح لك بالوصول لهذا الطبيب.');
        }

        $doctor->load('clinic');
        return view('dashboard.doctors.form', ['doctor' => $doctor, 'clinic' => $doctor->clinic]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        // Verify doctor belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $doctor->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.doctors.index')->with('error', 'غير مصرح لك بالتعديل على هذا الطبيب.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'job' => $validated['job'],
            'description' => $validated['description'] ?? null,
        ];

        // Handle image upload if present
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($doctor->image) {
                \Storage::disk('public')->delete($doctor->image);
            }
            $path = $request->file('image')->store('doctors', 'public');
            $updateData['image'] = $path;
        }

        $doctor->update($updateData);

        return redirect()->route('dashboard.doctors.index')->with('success', 'تم تحديث بيانات الطبيب.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        // Verify doctor belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $doctor->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.doctors.index')->with('error', 'غير مصرح لك بحذف هذا الطبيب.');
        }

        // Delete doctor image if exists
        if ($doctor->image) {
            \Storage::disk('public')->delete($doctor->image);
        }

        $doctor->delete();
        return redirect()->route('dashboard.doctors.index')->with('success', 'تم حذف الطبيب.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (! $clinic) {
            return redirect('/')->with('error', 'لا توجد عيادة مسجلة.');
        }
        $services = $clinic->services()->where('is_active', true)->get();

        return view('booking.form', compact('clinic', 'services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'string', 'max:10'],
            'service_id' => ['nullable', 'exists:services,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'الاسم مطلوب.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'appointment_date.required' => 'تاريخ الموعد مطلوب.',
            'appointment_date.after_or_equal' => 'التاريخ يجب أن يكون اليوم أو بعده.',
            'start_time.required' => 'وقت الموعد مطلوب.',
        ]);

        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (! $clinic) {
            return back()->withInput()->with('error', 'لا توجد عيادة مسجلة.');
        }

        $patient = Patient::firstOrCreate(
            [
                'clinic_id' => $clinic->id,
                'phone' => $validated['phone'],
            ],
            [
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'is_active' => true,
            ]
        );

        if ($patient->wasRecentlyCreated === false) {
            $patient->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? $patient->email,
            ]);
        }

        Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'service_id' => $validated['service_id'] ?? null,
            'status' => 'scheduled',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('booking.success')->with('success', 'تم تسجيل حجزك بنجاح. سنتواصل معك قريباً.');
    }

    public function success(): View
    {
        return view('booking.success');
    }
}

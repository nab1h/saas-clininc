<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
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

        // Show appointments ONLY for current logged-in clinic (not all clinics)
        $query = Appointment::with(['patient', 'service', 'clinic'])
            ->where('clinic_id', $currentClinicId);

        // Apply filters
        if ($request->has('status') && $request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('from') && $request->filled('from')) {
            $query->where('appointment_date', '>=', $request->input('from'));
        }
        if ($request->has('to') && $request->filled('to')) {
            $query->where('appointment_date', '<=', $request->input('to'));
        }

        // Order by date (newest first) then by time
        $query->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc');

        $bookings = $query->paginate(15)->withQueryString();

        return view('dashboard.booking.index', compact('bookings', 'clinic'));
    }

    public function show(Appointment $appointment): View
    {
        // Verify appointment belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $appointment->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.booking.index')->with('error', 'غير مصرح لك بالوصول لهذا الموعد.');
        }

        $appointment->load(['patient', 'service']);
        return view('dashboard.booking.show', ['booking' => $appointment]);
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:scheduled,confirmed,checked_in,in_progress,completed,cancelled,no_show'],
            'cancel_reason' => ['nullable', 'required_if:status,cancelled', 'string', 'max:255'],
        ]);

        $appointment->update([
            'status' => $request->status,
            'cancel_reason' => $request->status === 'cancelled' ? $request->cancel_reason : null,
        ]);

        return redirect()
            ->route('dashboard.booking.index')
            ->with('success', 'تم تحديث حالة الحجز.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        // Verify appointment belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $appointment->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.booking.index')->with('error', 'غير مصرح لك بحذف هذا الموعد.');
        }

        $appointment->delete();
        return redirect()->route('dashboard.booking.index')->with('success', 'تم حذف الموعد بنجاح.');
    }
}

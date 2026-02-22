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
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();

        $query = Appointment::with(['patient', 'service'])
            ->when($clinic, fn ($q) => $q->where('clinic_id', $clinic->id))
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('appointment_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('appointment_date', '<=', $request->to);
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('dashboard.booking.index', compact('bookings', 'clinic'));
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['patient', 'service', 'clinic']);
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
}

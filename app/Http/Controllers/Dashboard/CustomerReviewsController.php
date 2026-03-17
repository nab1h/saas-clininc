<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\CustomerReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerReviewsController extends Controller
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

        // Show customer reviews ONLY for current logged-in clinic (not all clinics)
        $customerReviews = CustomerReview::where('clinic_id', $currentClinicId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.customer-reviews.index', compact('customerReviews'));
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
            return redirect()->route('dashboard.customer-reviews.index')->with('error', 'العيادة غير موجودة.');
        }
        return view('dashboard.customer-reviews.form', ['customerReview' => null, 'clinic' => $clinic]);
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
            return redirect()->route('dashboard.customer-reviews.index')->with('error', 'العيادة غير موجودة.');
        }

        // Convert checkbox value to boolean before validation
        $request->merge([
            'is_approved' => $request->has('is_approved'),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
            'is_approved' => ['boolean'],
        ]);

        CustomerReview::create([
            'clinic_id' => $clinic->id,
            'name' => $validated['name'],
            'job_title' => $validated['job_title'],
            'message' => $validated['message'],
            'stars' => $validated['stars'],
            'is_approved' => $validated['is_approved'] ?? false,
        ]);

        return redirect()->route('dashboard.customer-reviews.index')->with('success', 'تمت إضافة تقييم العميل.');
    }

    public function edit(CustomerReview $customerReview): View
    {
        // Verify customer review belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $customerReview->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.customer-reviews.index')->with('error', 'غير مصرح لك بالوصول لهذا التقييم.');
        }

        $customerReview->load('clinic');
        return view('dashboard.customer-reviews.form', ['customerReview' => $customerReview, 'clinic' => $customerReview->clinic]);
    }

    public function update(Request $request, CustomerReview $customerReview): RedirectResponse
    {
        // Verify customer review belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $customerReview->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.customer-reviews.index')->with('error', 'غير مصرح لك بالتعديل على هذا التقييم.');
        }

        // Convert checkbox value to boolean before validation
        $request->merge([
            'is_approved' => $request->has('is_approved'),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
            'is_approved' => ['boolean'],
        ]);

        $customerReview->update([
            'name' => $validated['name'],
            'job_title' => $validated['job_title'],
            'message' => $validated['message'],
            'stars' => $validated['stars'],
            'is_approved' => $validated['is_approved'] ?? false,
        ]);

        return redirect()->route('dashboard.customer-reviews.index')->with('success', 'تم تحديث بيانات التقييم.');
    }

    public function destroy(CustomerReview $customerReview): RedirectResponse
    {
        // Verify customer review belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $customerReview->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.customer-reviews.index')->with('error', 'غير مصرح لك بحذف هذا التقييم.');
        }

        $customerReview->delete();
        return redirect()->route('dashboard.customer-reviews.index')->with('success', 'تم حذف التقييم.');
    }
}

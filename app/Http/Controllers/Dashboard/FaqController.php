<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (!$clinic) {
            return redirect()->route('dashboard.index')->with('error', 'العيادة غير موجودة.');
        }

        $faqs = Faq::where('clinic_id', $currentClinicId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('dashboard.faqs.index', compact('faqs'));
    }

    public function create(): View|RedirectResponse
    {
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (! $clinic) {
            return redirect()->route('dashboard.faqs.index')->with('error', 'العيادة غير موجودة.');
        }
        return view('dashboard.faqs.form', ['faq' => null, 'clinic' => $clinic]);
    }

    public function store(Request $request): RedirectResponse
    {
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (! $clinic) {
            return redirect()->route('dashboard.faqs.index')->with('error', 'العيادة غير موجودة.');
        }

        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Faq::create([
            'clinic_id' => $clinic->id,
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('dashboard.faqs.index')->with('success', 'تمت إضافة السؤال.');
    }

    public function edit(Faq $faq): View
    {
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $faq->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.faqs.index')->with('error', 'غير مصرح لك بالوصول لهذا السؤال.');
        }

        $faq->load('clinic');
        return view('dashboard.faqs.form', ['faq' => $faq, 'clinic' => $faq->clinic]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $faq->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.faqs.index')->with('error', 'غير مصرح لك بالتعديل على هذا السؤال.');
        }

        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('dashboard.faqs.index')->with('success', 'تم تحديث السؤال.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $faq->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.faqs.index')->with('error', 'غير مصرح لك بحذف هذا السؤال.');
        }

        $faq->delete();
        return redirect()->route('dashboard.faqs.index')->with('success', 'تم حذف السؤال.');
    }
}

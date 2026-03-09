<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Script;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScriptController extends Controller
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

        // Show scripts ONLY for current logged-in clinic (not all clinics)
        $scripts = Script::where('clinic_id', $currentClinicId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('dashboard.scripts.index', compact('scripts'));
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
            return redirect()->route('dashboard.scripts.index')->with('error', 'العيادة غير موجودة.');
        }
        return view('dashboard.scripts.form', ['script' => null, 'clinic' => $clinic]);
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
            return redirect()->route('dashboard.scripts.index')->with('error', 'العيادة غير موجودة.');
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(Script::TYPES))],
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string'],
            'position' => ['required', 'string', 'in:'.implode(',', array_keys(Script::POSITIONS))],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Script::create([
            'clinic_id' => $clinic->id,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'code' => $validated['code'],
            'position' => $validated['position'],
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.scripts.index')->with('success', 'تمت إضافة السكريبت.');
    }

    public function edit(Script $script): View
    {
        // Verify script belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $script->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.scripts.index')->with('error', 'غير مصرح لك بالوصول لهذا السكريبت.');
        }

        $script->load('clinic');
        return view('dashboard.scripts.form', ['script' => $script, 'clinic' => $script->clinic]);
    }

    public function update(Request $request, Script $script): RedirectResponse
    {
        // Verify script belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $script->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.scripts.index')->with('error', 'غير مصرح لك بالتعديل على هذا السكريبت.');
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(Script::TYPES))],
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string'],
            'position' => ['required', 'string', 'in:'.implode(',', array_keys(Script::POSITIONS))],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $script->update([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'code' => $validated['code'],
            'position' => $validated['position'],
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.scripts.index')->with('success', 'تم تحديث السكريبت.');
    }

    public function destroy(Script $script): RedirectResponse
    {
        // Verify script belongs to current clinic
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId && $script->clinic_id !== $currentClinicId) {
            return redirect()->route('dashboard.scripts.index')->with('error', 'غير مصرح لك بحذف هذا السكريبت.');
        }

        $script->delete();
        return redirect()->route('dashboard.scripts.index')->with('success', 'تم حذف السكريبت.');
    }
}

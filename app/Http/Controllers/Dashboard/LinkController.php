<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkController extends Controller
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

        // Show links ONLY for current logged-in clinic (not all clinics)
        $links = Link::where('clinic_id', $currentClinicId)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('dashboard.links.index', compact('links'));
    }

    public function create(): View|RedirectResponse
    {
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (! $clinic) {
            return redirect()->route('dashboard.links.index')->with('error', 'لا توجد عيادة. أضف عيادة أولاً.');
        }
        return view('dashboard.links.form', ['link' => null, 'clinic' => $clinic]);
    }

    public function store(Request $request): RedirectResponse
    {
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (! $clinic) {
            return redirect()->route('dashboard.links.index')->with('error', 'لا توجد عيادة.');
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(Link::TYPES))],
            'label' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Link::create([
            'clinic_id' => $clinic->id,
            'type' => $validated['type'],
            'label' => $validated['label'] ?? null,
            'url' => $validated['url'],
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.links.index')->with('success', 'تمت إضافة الرابط.');
    }

    public function edit(Link $link): View
    {
        $link->load('clinic');
        return view('dashboard.links.form', ['link' => $link, 'clinic' => $link->clinic]);
    }

    public function update(Request $request, Link $link): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(Link::TYPES))],
            'label' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:500'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $link->update([
            'type' => $validated['type'],
            'label' => $validated['label'] ?? null,
            'url' => $validated['url'],
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.links.index')->with('success', 'تم تحديث الرابط.');
    }

    public function destroy(Link $link): RedirectResponse
    {
        $link->delete();
        return redirect()->route('dashboard.links.index')->with('success', 'تم حذف الرابط.');
    }
}

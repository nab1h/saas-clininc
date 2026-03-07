<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ServiceController extends Controller
{
    protected function getCurrentClinic(): ?Clinic
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Get current clinic from session
        $currentClinicId = session('current_clinic_id');
        if ($currentClinicId) {
            return Clinic::find($currentClinicId);
        }

        // Fallback to user's first clinic
        return $user->clinics->first();
    }

    public function index(): View
    {
        $clinic = $this->getCurrentClinic();

        if (!$clinic) {
            return view('dashboard.services.index', ['services' => collect()]);
        }

        $services = $clinic->services()
            ->orderBy('name')
            ->get();

        return view('dashboard.services.index', compact('services'));
    }

    public function create(): View|RedirectResponse
    {
        $clinic = $this->getCurrentClinic();
        if (! $clinic) {
            return redirect()->route('dashboard.services.index')->with('error', 'لا توجد عيادة. يجب تعيينك لعيادة أولاً.');
        }
        return view('dashboard.services.form', ['service' => null, 'clinic' => $clinic]);
    }

    public function store(Request $request): RedirectResponse
    {
        $clinic = $this->getCurrentClinic();
        if (!$clinic) {
            return redirect()->route('dashboard.services.index')->with('error', 'لا توجد عيادة. يجب تعيينك لعيادة أولاً.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:480'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'clinic_id' => $clinic->id,
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'category' => $validated['category'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        Service::create($data);

        return redirect()->route('dashboard.services.index')->with('success', 'تمت إضافة الخدمة.');
    }

    public function edit(Service $service): View
    {
        $service->load('clinic');
        return view('dashboard.services.form', ['service' => $service, 'clinic' => $service->clinic]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:480'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $validated['name'],
            'name_ar' => $validated['name_ar'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'category' => $validated['category'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return redirect()->route('dashboard.services.index')->with('success', 'تم تحديث الخدمة.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();
        return redirect()->route('dashboard.services.index')->with('success', 'تم حذف الخدمة.');
    }
}

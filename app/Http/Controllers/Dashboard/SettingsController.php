<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Clinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    /** أحجام الأيقونات الشائعة (بكسل) */
    public const ICON_SIZES = [
        'favicon' => 32,
        'icon_16' => 16,
        'icon_32' => 32,
        'icon_48' => 48,
        'icon_180' => 180,
        'icon_192' => 192,
        'icon_512' => 512,
    ];

    public function index(): View|RedirectResponse
    {
        // Get current clinic from session (set by CheckUserClinic middleware)
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة. اختر عيادة أولاً.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (!$clinic) {
            return redirect()->route('dashboard.index')->with('error', 'العيادة غير موجودة.');
        }

        return view('dashboard.settings.index', compact('clinic'));
    }

    public function update(Request $request): RedirectResponse
    {
        // Get current clinic from session (set by CheckUserClinic middleware)
        $currentClinicId = session('current_clinic_id');
        if (!$currentClinicId) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة محددة.');
        }

        $clinic = Clinic::find($currentClinicId);
        if (!$clinic) {
            return redirect()->route('dashboard.settings.index')->with('error', 'العيادة غير موجودة.');
        }
        if (!$clinic) {
            return redirect()->route('dashboard.settings.index')->with('error', 'لا توجد عيادة.');
        }

        // Validation - only validate fields that are present in the request
        $rules = [
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:2048'],
            'content' => ['nullable', 'string'],
            'message' => ['nullable', 'string'],
            'footer_text' => ['nullable', 'string', 'max:1000'],
            'brand_color' => ['nullable', 'string', 'max:7'],
            'primary_color' => ['nullable', 'string', 'max:7'],
            'favicon' => ['nullable', 'file', 'mimes:png,jpg,jpeg,ico', 'max:1024'],
            'icon_16' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'icon_32' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'icon_48' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'icon_180' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'icon_192' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'icon_512' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];

        // Add basic info rules only if fields are present and not empty
        if ($request->filled('name')) {
            $rules['name'] = ['required', 'string', 'max:255'];
        }
        if ($request->filled('email')) {
            $rules['email'] = ['nullable', 'email', 'max:255'];
        }
        if ($request->filled('phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }
        if ($request->filled('address')) {
            $rules['address'] = ['nullable', 'string', 'max:500'];
        }
        if ($request->filled('google_maps')) {
            $rules['google_maps'] = ['nullable', 'string', 'max:1000'];
        }
        if ($request->filled('working_hours')) {
            $rules['working_hours'] = ['nullable', 'string', 'max:500'];
        }

        $validated = $request->validate($rules);

        // Update basic info - only if fields are present and not empty
        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->input('name');
        }
        if ($request->filled('email')) {
            $data['email'] = $request->input('email');
        }
        if ($request->filled('phone')) {
            $data['phone'] = $request->input('phone');
        }
        if ($request->filled('address')) {
            $data['address'] = $request->input('address');
        }

        // Update settings - only update fields that are present and not empty
        $settings = $clinic->settings ?? [];

        // Basic info settings
        if ($request->filled('google_maps')) {
            $settings['google_maps'] = $request->input('google_maps');
        }
        if ($request->filled('working_hours')) {
            $settings['working_hours'] = $request->input('working_hours');
        }

        // Handle logo (upload or remove)
        if ($request->input('remove_logo') == '1') {
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            $data['logo'] = $request->file('logo')->store('clinic', 'public');
        }

        // Content settings
        if ($request->filled('content')) {
            $settings['content'] = $request->input('content');
        }
        if ($request->filled('message')) {
            $settings['message'] = $request->input('message');
        }
        if ($request->filled('footer_text')) {
            $settings['footer_text'] = $request->input('footer_text');
        }

        // Brand colors
        if ($request->filled('brand_color')) {
            $settings['brand_color'] = $request->input('brand_color');
        }
        if ($request->filled('primary_color')) {
            $settings['primary_color'] = $request->input('primary_color');
        }

        // Handle icons (upload or remove)
        $iconFields = ['favicon', 'icon_16', 'icon_32', 'icon_48', 'icon_180', 'icon_192', 'icon_512'];
        foreach ($iconFields as $field) {
            // Check if removal is requested
            if ($request->input('remove_' . $field) == '1') {
                $oldPath = $settings[$field] ?? null;
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $settings[$field] = null;
            } elseif ($request->hasFile($field)) {
                $oldPath = $settings[$field] ?? null;
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
                $settings[$field] = $request->file($field)->store('clinic/icons', 'public');
            }
        }

        $data['settings'] = $settings;

        $clinic->update($data);

        return redirect()->route('dashboard.settings.index')->with('success', 'تم حفظ الإعدادات.');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (! $clinic) {
            return redirect()->route('dashboard.index')->with('error', 'لا توجد عيادة. أضف عيادة أولاً.');
        }
        return view('dashboard.settings.index', compact('clinic'));
    }

    public function update(Request $request): RedirectResponse
    {
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (! $clinic) {
            return redirect()->route('dashboard.settings.index')->with('error', 'لا توجد عيادة.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:2048'],
            'content' => ['nullable', 'string'],
            'message' => ['nullable', 'string'],
            'favicon' => ['nullable', 'file', 'mimes:png,jpg,jpeg,ico', 'max:1024'],
            'icon_16' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'icon_32' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'icon_48' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:512'],
            'icon_180' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'icon_192' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'icon_512' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $data = ['name' => $validated['name']];

        if ($request->hasFile('logo')) {
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            $data['logo'] = $request->file('logo')->store('clinic', 'public');
        }

        $settings = $clinic->settings ?? [];

        $settings['content'] = $request->input('content');
        $settings['message'] = $request->input('message');

        $iconFields = ['favicon', 'icon_16', 'icon_32', 'icon_48', 'icon_180', 'icon_192', 'icon_512'];
        foreach ($iconFields as $field) {
            if ($request->hasFile($field)) {
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

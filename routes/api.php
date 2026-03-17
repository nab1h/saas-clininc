<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Models\Link;
use App\Models\Service;
use App\Models\Comment;
use App\Models\Patient;
use App\Models\Clinic;
use App\Models\Script;
use App\Models\Doctor;
use App\Models\Faq;
use App\Models\User;
use App\Models\Role;
use App\Models\CustomerReview;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/{slug}/articles', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    $articles = Article::where('clinic_id', $clinic->id)->get();

    // Load comments for each article
    $articleIds = $articles->pluck('id');
    $comments = Comment::whereIn('article_id', $articleIds)->get()->groupBy('article_id');

    return $articles->map(function ($article) use ($comments) {
        $articleComments = $comments->get($article->id) ?? collect();
        return [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'body' => $article->body,
            'excerpt' => $article->excerpt ?? null,
            'featured_image' => $article->image ?? null,
            'is_published' => $article->is_published,
            'is_favorite' => $article->is_favorite ?? false,
            'created_at' => $article->created_at,
            'updated_at' => $article->updated_at,
            'comment_count' => $articleComments->count(),
            'comments' => $articleComments->values(),
        ];
    });
});

Route::get('/{slug}/links', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return Link::where('clinic_id', $clinic->id)->get();
});

Route::get('/{slug}/scripts', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return Script::where('clinic_id', $clinic->id)->get();
});

Route::get('/{slug}/doctors', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return Doctor::where('clinic_id', $clinic->id)->get();
});

Route::get('/{slug}/faqs', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return Faq::where('clinic_id', $clinic->id)
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
});

Route::get('/{slug}/services', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return Service::where('clinic_id', $clinic->id)->get();
});

Route::get('/{slug}/settings', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return response()->json([
        'name' => $clinic->name,
        'email' => $clinic->email,
        'phone' => $clinic->phone,
        'address' => $clinic->address,
        'logo' => $clinic->logo,
        'favicon' => $clinic->settings['favicon'] ?? null,
        'icon_16' => $clinic->settings['icon_16'] ?? null,
        'icon_32' => $clinic->settings['icon_32'] ?? null,
        'icon_48' => $clinic->settings['icon_48'] ?? null,
        'icon_180' => $clinic->settings['icon_180'] ?? null,
        'icon_192' => $clinic->settings['icon_192'] ?? null,
        'icon_512' => $clinic->settings['icon_512'] ?? null,
        'settings' => $clinic->settings,
    ]);
});

Route::get('/{slug}/comments', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    $articleIds = Article::where('clinic_id', $clinic->id)->pluck('id');
    return Comment::whereIn('article_id', $articleIds)->get();
});

Route::post('/{slug}/comments', function (Request $request, $slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();

    $validated = $request->validate([
        'article_id' => 'required|exists:articles,id',
        'guest_name' => 'required|string|max:255',
        'body' => 'required|string|max:2000',
        'parent_id' => 'nullable|exists:comments,id',
        'guest_email' => 'nullable|email|max:255',
    ]);

    // Verify that the article belongs to this clinic
    $article = Article::where('id', $validated['article_id'])
        ->where('clinic_id', $clinic->id)
        ->first();

    if (!$article) {
        return response()->json([
            'message' => 'المقال غير موجود أو لا ينتمي لهذه العيادة',
        ], 404);
    }

    // Verify parent comment belongs to the same clinic's article
    if (!empty($validated['parent_id'])) {
        $parentComment = Comment::find($validated['parent_id']);
        $parentArticle = $parentComment ? Article::find($parentComment->article_id) : null;

        if (!$parentArticle || $parentArticle->clinic_id !== $clinic->id) {
            return response()->json([
                'message' => 'التعليق الأصل غير صحيح',
            ], 404);
        }
    }

    $comment = Comment::create([
        'article_id' => $validated['article_id'],
        'guest_name' => $validated['guest_name'],
        'body' => $validated['body'],
        'parent_id' => $validated['parent_id'] ?? null,
        'guest_email' => $validated['guest_email'] ?? null,
        'user_id' => null,
        'is_approved' => false,
    ]);

    return response()->json([
        'message' => 'تم إضافة التعليق بنجاح',
        'comment' => $comment,
    ], 201);
});

// Get available services for booking
Route::get('/{slug}/booking/services', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    $services = Service::where('clinic_id', $clinic->id)
        ->where('is_active', true)
        ->get(['id', 'name', 'description', 'duration_minutes', 'price']);

    return response()->json([
        'clinic' => [
            'id' => $clinic->id,
            'name' => $clinic->name,
            'slug' => $clinic->slug,
        ],
        'services' => $services,
    ]);
});

// Get available time slots for booking
Route::get('/{slug}/booking/slots', function (Request $request, $slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();

    $validated = $request->validate([
        'date' => 'required|date',
        'service_id' => 'nullable|exists:services,id',
    ]);

    $date = \Carbon\Carbon::parse($validated['date']);

    // Get existing appointments for this date
    $query = \App\Models\Appointment::where('clinic_id', $clinic->id)
        ->where('appointment_date', $date->format('Y-m-d'))
        ->whereIn('status', ['scheduled', 'confirmed']);

    if ($validated['service_id'] ?? null) {
        $service = Service::find($validated['service_id']);
        if ($service) {
            $query->where('service_id', $service->id);
        }
    }

    $existingAppointments = $query->get();

    // Generate time slots (every 30 minutes from 9 AM to 5 PM)
    $slots = [];
    $startTime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' 09:00:00');
    $endTime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' 17:00:00');

    while ($startTime < $endTime) {
        $slotEnd = $startTime->copy()->addMinutes(30);

        // Check if this slot is available
        $isAvailable = true;
        foreach ($existingAppointments as $appointment) {
            $appointmentStart = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time);
            $appointmentEnd = $appointmentStart->copy()->addMinutes(30);

            // Check overlap
            if (($startTime >= $appointmentStart && $startTime < $appointmentEnd) ||
                ($slotEnd > $appointmentStart && $slotEnd <= $appointmentEnd)) {
                $isAvailable = false;
                break;
            }
        }

        $slots[] = [
            'time' => $startTime->format('H:i'),
            'available' => $isAvailable,
            'start' => $startTime->toIso8601String(),
            'end' => $slotEnd->toIso8601String(),
        ];

        $startTime = $slotEnd;
    }

    return response()->json([
        'date' => $date->format('Y-m-d'),
        'slots' => $slots,
        'available_count' => count(array_filter($slots, fn($s) => $s['available'])),
    ]);
});

// Create new booking/appointment
Route::post('/{slug}/booking', function (Request $request, $slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:50',
        'email' => 'nullable|email|max:255',
        'appointment_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|string|max:10',
        'service_id' => 'nullable|exists:services,id',
        'notes' => 'nullable|string|max:1000',
    ], [
        'name.required' => 'الاسم مطلوب.',
        'phone.required' => 'رقم الهاتف مطلوب.',
        'appointment_date.required' => 'تاريخ الموعد مطلوب.',
        'appointment_date.after_or_equal' => 'التاريخ يجب أن يكون اليوم أو بعده.',
        'start_time.required' => 'وقت الموعد مطلوب.',
        'phone.regex' => 'رقم الهاتف غير صحيح.',
    ]);

    // Verify service belongs to this clinic
    if ($validated['service_id'] ?? null) {
        $service = Service::where('id', $validated['service_id'])
            ->where('clinic_id', $clinic->id)
            ->first();

        if (!$service) {
            return response()->json([
                'message' => 'الخدمة غير موجودة أو لا تنتمي لهذه العيادة',
            ], 404);
        }
    }

    // Check if time slot is available
    $appointmentDateTime = \Carbon\Carbon::parse($validated['appointment_date'] . ' ' . $validated['start_time']);
    $existingAppointment = \App\Models\Appointment::where('clinic_id', $clinic->id)
        ->where('appointment_date', $validated['appointment_date'])
        ->where('start_time', $validated['start_time'])
        ->whereIn('status', ['scheduled', 'confirmed'])
        ->first();

    if ($existingAppointment) {
        return response()->json([
            'message' => 'هذا الموعد محجوز بالفعل. اختر وقت آخر.',
        ], 400);
    }

    // Create or update patient
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

    // Create appointment
    \Illuminate\Support\Facades\Log::info('Creating appointment', [
        'clinic_id' => $clinic->id,
        'clinic_name' => $clinic->name,
        'phone' => $validated['phone'],
        'name' => $validated['name'],
        'email' => $validated['email'] ?? null,
        'appointment_date' => $validated['appointment_date'],
        'start_time' => $validated['start_time'],
        'service_id' => $validated['service_id'] ?? null,
        'notes' => $validated['notes'] ?? null,
    ]);

    $appointment = \App\Models\Appointment::create([
        'clinic_id' => $clinic->id,
        'patient_id' => $patient->id,
        'appointment_date' => $validated['appointment_date'],
        'start_time' => $validated['start_time'],
        'service_id' => $validated['service_id'] ?? null,
        'status' => 'scheduled',
        'notes' => $validated['notes'] ?? null,
    ]);

    \Illuminate\Support\Facades\Log::info('Appointment created successfully', [
        'appointment_id' => $appointment->id,
        'clinic_id' => $appointment->clinic_id,
        'patient_id' => $appointment->patient_id,
        'appointment_date' => $appointment->appointment_date,
        'start_time' => $appointment->start_time,
        'service_id' => $appointment->service_id,
        'status' => $appointment->status,
        'notes' => $appointment->notes,
    ]);

    return response()->json([
        'message' => 'تم تسجيل الحجز بنجاح',
        'clinic_id' => $clinic->id,
        'clinic_name' => $clinic->name,
        'clinic_slug' => $clinic->slug,
        'appointment' => [
            'id' => $appointment->id,
            'appointment_date' => $appointment->appointment_date,
            'start_time' => $appointment->start_time,
            'status' => $appointment->status,
        ],
        'patient' => [
            'id' => $patient->id,
            'name' => $patient->name,
            'phone' => $patient->phone,
        ],
    ], 201);
});

// Get customer reviews (public, only approved)
Route::get('/{slug}/customer-reviews', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return CustomerReview::where('clinic_id', $clinic->id)
        ->where('is_approved', true)
        ->orderBy('created_at', 'desc')
        ->get();
});

// Add new customer review (pending approval)
Route::post('/{slug}/customer-reviews', function (Request $request, $slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'job_title' => 'required|string|max:255',
        'message' => 'required|string|max:2000',
        'stars' => 'required|integer|min:1|max:5',
    ], [
        'name.required' => 'الاسم مطلوب.',
        'job_title.required' => 'المسمى الوظيفي مطلوب.',
        'message.required' => 'الرسالة مطلوبة.',
        'stars.required' => 'التقييم مطلوب.',
        'stars.min' => 'التقييم يجب أن يكون على الأقل 1 نجمة.',
        'stars.max' => 'التقييم يجب أن يكون على الأكثر 5 نجوم.',
    ]);

    $review = CustomerReview::create([
        'clinic_id' => $clinic->id,
        'name' => $validated['name'],
        'job_title' => $validated['job_title'],
        'message' => $validated['message'],
        'stars' => $validated['stars'],
        'is_approved' => false,
    ]);

    return response()->json([
        'message' => 'تم إرسال تقييمك بنجاح وسيتم مراجعته',
        'review' => $review,
    ], 201);
});


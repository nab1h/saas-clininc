<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Models\Link;
use App\Models\Service;
use App\Models\Comment;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Role;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/{slug}/articles', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return Article::where('clinic_id', $clinic->id)->get();
});

Route::get('/{slug}/links', function ($slug) {
    $clinic = Clinic::where('slug', $slug)->firstOrFail();
    return Link::where('clinic_id', $clinic->id)->get();
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

Route::get('/clinics/{clinic}/users', function (Clinic $clinic) {
    return response()->json([
        'users' => $clinic->users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_name' => $user->pivot->role_id ? Role::find($user->pivot->role_id)?->name : 'بدون دور',
            ];
        }),
    ]);
});

Route::get('/users/{userId}/clinics', function ($userId) {
    $user = User::findOrFail($userId);
    return response()->json([
        'clinics' => $user->clinics->map(function ($clinic) {
            return [
                'clinic_id' => $clinic->id,
                'name' => $clinic->name,
                'role_name' => $clinic->pivot->role_id ? Role::find($clinic->pivot->role_id)?->name : 'بدون دور',
            ];
        }),
    ]);
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
        'message' => 'done',
        'comment' => $comment,
    ], 201);
});


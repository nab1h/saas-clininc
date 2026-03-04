<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Clinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();

        $query = Article::when($clinic, fn ($q) => $q->where('clinic_id', $clinic->id))
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            } elseif ($request->status === 'favorites') {
                $query->where('is_favorite', true);
            }
        }

        $articles = $query->get();
        $status = $request->status ?? 'all';

        return view('dashboard.articles.index', compact('articles', 'status', 'clinic'));
    }

    public function create(): View|RedirectResponse
    {
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (!$clinic) {
            return redirect()->route('dashboard.articles.index')->with('error', 'لا توجد عيادة. أضف عيادة أولاً.');
        }
        return view('dashboard.articles.form', ['article' => null, 'clinic' => $clinic]);
    }

    public function store(Request $request): RedirectResponse
    {
        $clinic = Clinic::where('is_active', true)->first() ?? Clinic::first();
        if (!$clinic) {
            return redirect()->route('dashboard.articles.index')->with('error', 'لا توجد عيادة.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $userId = auth()->id() ?? 1;

        $data = [
            'clinic_id' => $clinic->id,
            'user_id' => $userId,
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? str()->slug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('dashboard.articles.index')->with('success', 'تمت إضافة المقال.');
    }

    public function show(Article $article): View
    {
        $article->load(['user', 'clinic']);
        $article->increment('views_count');

        return view('dashboard.articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        $article->load('clinic');
        return view('dashboard.articles.form', ['article' => $article, 'clinic' => $article->clinic]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug,' . $article->id],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data = [
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? str()->slug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') && !$article->published_at ? now() : $article->published_at,
        ];

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('dashboard.articles.index')->with('success', 'تم تحديث المقال.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        $article->delete();
        return redirect()->route('dashboard.articles.index')->with('success', 'تم حذف المقال.');
    }

    public function toggleFavorite(Request $request, Article $article): RedirectResponse
    {
        $article->is_favorite = !$article->is_favorite;
        $article->save();

        return back()->with('success', $article->is_favorite ? 'تمت إضافة المقال إلى المفضلة.' : 'تمت إزالة المقال من المفضلة.');
    }
}

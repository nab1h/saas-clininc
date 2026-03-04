@extends('dashboard.layout')

@section('title', $article ? 'تعديل المقال' : 'إضافة مقال')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $article ? 'تعديل المقال' : 'إضافة مقال' }}</h1>
        <a href="{{ route('dashboard.articles.index') }}" class="btn btn-outline-secondary">← رجوع</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ $article ? route('dashboard.articles.update', $article) : route('dashboard.articles.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @if($article) @method('put') @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">عنوان المقال <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $article?->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الرابط المخصص (Slug)</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $article?->slug) }}" placeholder="يُنشأ تلقائياً من العنوان إذا ترك فارغاً">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">مقتطف المقال</label>
                            <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $article?->excerpt) }}</textarea>
                            <small class="text-muted">مقتطف قصير يظهر في قائمة المقالات (حد أقصى 500 حرف)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">محتوى المقال</label>
                            <textarea name="body" class="form-control" rows="10">{{ old('body', $article?->body) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" {{ old('is_published', $article?->is_published ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">نشر المقال الآن</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">صورة المقال</label>
                            @if($article?->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="" class="img-thumbnail mb-2" style="max-height: 200px;">
                                    <p class="small text-muted">الصورة الحالية. رفع صورة جديدة يستبدلها.</p>
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">jpeg, png, gif, webp — حد أقصى 2 ميجا</small>
                        </div>

                        @if($article)
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">معلومات المقال</h6>
                                    <p class="mb-1 small"><strong>المشاهدات:</strong> {{ $article->views_count }}</p>
                                    <p class="mb-1 small"><strong>تاريخ الإنشاء:</strong> {{ $article->created_at->format('Y-m-d') }}</p>
                                    @if($article->published_at)
                                        <p class="mb-0 small"><strong>تاريخ النشر:</strong> {{ $article->published_at->format('Y-m-d') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $article ? 'حفظ التعديلات' : 'إضافة المقال' }}</button>
                <a href="{{ route('dashboard.articles.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

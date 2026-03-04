@extends('dashboard.layout')

@section('title', $article->title)

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">
            {{ $article->title }}
            @if($article->is_favorite)
                <i class="fas fa-star text-warning ms-2"></i>
            @endif
        </h1>
        <div class="d-flex gap-2">
            <form action="{{ route('dashboard.articles.favorite', $article) }}" method="post">
                @csrf
                <button type="submit" class="btn {{ $article->is_favorite ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="fas fa-star"></i> {{ $article->is_favorite ? 'في المفضلة' : 'أضف للمفضلة' }}
                </button>
            </form>
            <a href="{{ route('dashboard.articles.edit', $article) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> تعديل
            </a>
            <a href="{{ route('dashboard.articles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-1"></i> رجوع
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="img-fluid rounded mb-4" style="max-height: 400px; object-fit: cover; width: 100%;">
            @endif

            @if($article->excerpt)
                <div class="lead mb-4 text-muted">{{ $article->excerpt }}</div>
            @endif

            <div class="article-body mb-4">
                {!! nl2br($article->body ?? 'لا يوجد محتوى') !!}
            </div>

            <div class="d-flex justify-content-between text-muted small border-top pt-3 mt-4">
                <div>
                    <i class="fas fa-user me-1"></i> الكاتب: {{ $article->user?->name ?? 'غير معروف' }}
                </div>
                <div>
                    <i class="fas fa-calendar me-1"></i> {{ $article->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-1"></i> تفاصيل المقال</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">الحالة</small><br>
                        <span class="badge bg-{{ $article->is_published ? 'success' : 'secondary' }}">
                            {{ $article->is_published ? 'منشور' : 'مسودة' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">المفضلة</small><br>
                        @if($article->is_favorite)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> مفضل
                            </span>
                        @else
                            <span class="text-muted">غير مفضل</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">المشاهدات</small><br>
                        <strong>{{ $article->views_count }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">الرابط المخصص</small><br>
                        <code>{{ $article->slug }}</code>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">تاريخ الإنشاء</small><br>
                        {{ $article->created_at->format('Y-m-d H:i') }}
                    </div>
                    @if($article->published_at)
                        <div class="mb-0">
                            <small class="text-muted">تاريخ النشر</small><br>
                            {{ $article->published_at->format('Y-m-d H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-share-alt me-1"></i> مشاركة</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('dashboard.articles.show', $article) }}" target="_blank" class="btn btn-primary">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ route('dashboard.articles.show', $article) }}&text={{ $article->title }}" target="_blank" class="btn btn-info">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ $article->title }}%20{{ route('dashboard.articles.show', $article) }}" target="_blank" class="btn btn-success">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

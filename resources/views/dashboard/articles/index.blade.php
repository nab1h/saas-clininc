@extends('dashboard.layout')

@section('title', 'المقالات')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">المقالات</h1>
        <a href="{{ route('dashboard.articles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> إضافة مقال
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div><i class="fas fa-newspaper me-1"></i> قائمة المقالات</div>
                <div>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('dashboard.articles.index', ['status' => 'all']) }}"
                           class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">الكل</a>
                        <a href="{{ route('dashboard.articles.index', ['status' => 'published']) }}"
                           class="btn {{ $status === 'published' ? 'btn-primary' : 'btn-outline-primary' }}">منشورة</a>
                        <a href="{{ route('dashboard.articles.index', ['status' => 'draft']) }}"
                           class="btn {{ $status === 'draft' ? 'btn-primary' : 'btn-outline-primary' }}">مسودة</a>
                        <a href="{{ route('dashboard.articles.index', ['status' => 'favorites']) }}"
                           class="btn {{ $status === 'favorites' ? 'btn-warning' : 'btn-outline-warning' }}">
                            <i class="fas fa-star"></i> المفضلة
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">مفضلة</th>
                            <th style="width: 80px;">الصورة</th>
                            <th>العنوان</th>
                            <th>المقتطف</th>
                            <th>الكاتب</th>
                            <th>المشاهدات</th>
                            <th>الحالة</th>
                            <th style="width: 180px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td class="text-center">
                                    <form action="{{ route('dashboard.articles.favorite', $article) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 {{ $article->is_favorite ? 'text-warning' : 'text-muted' }}">
                                            <i class="fas fa-star fa-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" alt="" class="rounded" style="width: 56px; height: 56px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 56px; height: 56px;">
                                            <i class="fas fa-image fa-lg"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.articles.show', $article) }}" class="text-decoration-none fw-bold">
                                        {{ $article->title }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($article->excerpt, 60) ?? '—' }}</td>
                                <td>{{ $article->user?->name ?? '—' }}</td>
                                <td>{{ $article->views_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $article->is_published ? 'success' : 'secondary' }}">
                                        {{ $article->is_published ? 'منشور' : 'مسودة' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.articles.show', $article) }}" class="btn btn-sm btn-outline-secondary" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('dashboard.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                    <form action="{{ route('dashboard.articles.destroy', $article) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا المقال؟');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">لا توجد مقالات. <a href="{{ route('dashboard.articles.create') }}">إضافة مقال</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

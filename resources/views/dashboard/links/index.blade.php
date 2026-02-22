@extends('dashboard.layout')

@section('title', 'روابط السوشيال ميديا')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">روابط السوشيال ميديا</h1>
        <a href="{{ route('dashboard.links.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> إضافة رابط</a>
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
        <div class="card-header"><i class="fas fa-link me-1"></i> قائمة الروابط</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>النوع</th>
                            <th>الاسم / التسمية</th>
                            <th>الرابط</th>
                            <th style="width: 70px;">الترتيب</th>
                            <th style="width: 80px;">الحالة</th>
                            <th style="width: 160px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($links as $link)
                            <tr>
                                <td>{{ $link->id }}</td>
                                <td>
                                    @php
                                        $icons = [
                                            'facebook' => 'fab fa-facebook text-primary',
                                            'instagram' => 'fab fa-instagram text-danger',
                                            'twitter' => 'fab fa-twitter',
                                            'youtube' => 'fab fa-youtube text-danger',
                                            'whatsapp' => 'fab fa-whatsapp text-success',
                                            'website' => 'fas fa-globe text-info',
                                            'tiktok' => 'fab fa-tiktok',
                                            'linkedin' => 'fab fa-linkedin text-primary',
                                            'telegram' => 'fab fa-telegram text-info',
                                            'snapchat' => 'fab fa-snapchat-ghost text-warning',
                                        ];
                                    @endphp
                                    <i class="{{ $icons[$link->type] ?? 'fas fa-link' }} me-1"></i>
                                    {{ \App\Models\Link::TYPES[$link->type] ?? $link->type }}
                                </td>
                                <td>{{ $link->label ?? '—' }}</td>
                                <td>
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener" class="text-break">{{ Str::limit($link->url, 45) }}</a>
                                </td>
                                <td>{{ $link->order }}</td>
                                <td>
                                    <span class="badge bg-{{ $link->is_active ? 'success' : 'secondary' }}">
                                        {{ $link->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="فتح"><i class="fas fa-external-link-alt"></i></a>
                                    <a href="{{ route('dashboard.links.edit', $link) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                    <form action="{{ route('dashboard.links.destroy', $link) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا الرابط؟');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">لا توجد روابط. <a href="{{ route('dashboard.links.create') }}">إضافة رابط</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

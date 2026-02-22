@extends('dashboard.layout')

@section('title', 'الخدمات')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">الخدمات</h1>
        <a href="{{ route('dashboard.services.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> إضافة خدمة</a>
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
        <div class="card-header"><i class="fas fa-concierge-bell me-1"></i> قائمة الخدمات</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">الصورة</th>
                            <th>الاسم</th>
                            <th>الاسم (عربي)</th>
                            <th>السعر</th>
                            <th>المدة</th>
                            <th>التصنيف</th>
                            <th>الحالة</th>
                            <th style="width: 140px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $s)
                            <tr>
                                <td>
                                    @if($s->image)
                                        <img src="{{ asset('storage/' . $s->image) }}" alt="" class="rounded" style="width: 56px; height: 56px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 56px; height: 56px;">
                                            <i class="fas fa-image fa-lg"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->name_ar ?? '—' }}</td>
                                <td>{{ number_format($s->price, 2) }}</td>
                                <td>{{ $s->duration_minutes }} د</td>
                                <td>{{ $s->category ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $s->is_active ? 'success' : 'secondary' }}">
                                        {{ $s->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.services.edit', $s) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                    <form action="{{ route('dashboard.services.destroy', $s) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذه الخدمة؟');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">لا توجد خدمات.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

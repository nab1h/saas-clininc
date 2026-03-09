@extends('dashboard.layout')

@section('title', 'السكريبتات')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">السكريبتات</h1>
        <a href="{{ route('dashboard.scripts.create') }}" class="btn btn-primary">+ إضافة سكريبت جديد</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($scripts->count() === 0)
                <div class="text-center py-5">
                    <i class="fas fa-code fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد سكريبتات حالياً</p>
                    <a href="{{ route('dashboard.scripts.create') }}" class="btn btn-primary">أضف سكريبت جديد</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>النوع</th>
                                <th>المكان</th>
                                <th>الترتيب</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scripts as $index => $script)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $script->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $script->type_name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $script->position_name }}</span>
                                    </td>
                                    <td>{{ $script->order ?? 0 }}</td>
                                    <td>
                                        @if($script->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('dashboard.scripts.edit', $script) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.scripts.destroy', $script) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا السكريبت؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

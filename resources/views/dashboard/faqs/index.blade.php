@extends('dashboard.layout')

@section('title', 'الأسئلة الشائعة')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">الأسئلة الشائعة</h1>
        <a href="{{ route('dashboard.faqs.create') }}" class="btn btn-primary">+ إضافة سؤال جديد</a>
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
            @if($faqs->count() === 0)
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد أسئلة حالياً</p>
                    <a href="{{ route('dashboard.faqs.create') }}" class="btn btn-primary">أضف سؤال جديد</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الترتيب</th>
                                <th>السؤال</th>
                                <th>الإجابة</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $index => $faq)
                                <tr class="{{ !$faq->is_active ? 'table-secondary' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $faq->order }}</td>
                                    <td>{{ $faq->question }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit($faq->answer, 100) !!}</td>
                                    <td>
                                        @if($faq->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('dashboard.faqs.edit', $faq) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.faqs.destroy', $faq) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟');">
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

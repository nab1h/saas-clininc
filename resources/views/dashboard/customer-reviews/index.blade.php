@extends('dashboard.layout')

@section('title', 'تقييمات العملاء')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">تقييمات العملاء</h1>
        <a href="{{ route('dashboard.customer-reviews.create') }}" class="btn btn-primary">+ إضافة تقييم جديد</a>
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
            @if($customerReviews->count() === 0)
                <div class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا يوجد تقييمات حالياً</p>
                    <a href="{{ route('dashboard.customer-reviews.create') }}" class="btn btn-primary">أضف تقييم جديد</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>المسمى الوظيفي</th>
                                <th>التقييم</th>
                                <th>الرسالة</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customerReviews as $index => $review)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $review->name }}</td>
                                    <td>{{ $review->job_title }}</td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->stars ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-2">({{ $review->stars }}/5)</span>
                                    </td>
                                    <td>{!! \Illuminate\Support\Str::limit($review->message, 100) !!}</td>
                                    <td>
                                        @if($review->is_approved)
                                            <span class="badge bg-success">معتمد</span>
                                        @else
                                            <span class="badge bg-secondary">غير معتمد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('dashboard.customer-reviews.edit', $review) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.customer-reviews.destroy', $review) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقييم؟');">
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

@extends('dashboard.layout')

@section('title', 'الأطباء')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">الأطباء</h1>
        <a href="{{ route('dashboard.doctors.create') }}" class="btn btn-primary">+ إضافة طبيب جديد</a>
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
            @if($doctors->count() === 0)
                <div class="text-center py-5">
                    <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا يوجد أطباء حالياً</p>
                    <a href="{{ route('dashboard.doctors.create') }}" class="btn btn-primary">أضف طبيب جديد</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>التخصص (الوظيفة)</th>
                                <th>الوصف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $index => $doctor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($doctor->image)
                                            <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user-md"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $doctor->name }}</td>
                                    <td>{{ $doctor->job }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit($doctor->description ?? '—', 100) !!}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('dashboard.doctors.edit', $doctor) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('dashboard.doctors.destroy', $doctor) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطبيب؟');">
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

@extends('dashboard.layout')

@section('title', $doctor ? 'تعديل الطبيب' : 'إضافة طبيب')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $doctor ? 'تعديل الطبيب' : 'إضافة طبيب' }}</h1>
        <a href="{{ route('dashboard.doctors.index') }}" class="btn btn-outline-secondary">← رجوع</a>
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
            <form action="{{ $doctor ? route('dashboard.doctors.update', $doctor) : route('dashboard.doctors.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @if($doctor) @method('put') @endif

                <div class="mb-3">
                    <label class="form-label">اسم الطبيب <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $doctor?->name) }}" placeholder="مثال: د. أحمد محمد" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">التخصص / الوظيفة <span class="text-danger">*</span></label>
                    <input type="text" name="job" class="form-control" value="{{ old('job', $doctor?->job) }}" placeholder="مثال: طبيب قلبية" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="نبذة عن الطبيب...">{{ old('description', $doctor?->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">صورة الطبيب</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($doctor && $doctor->image)
                        <div class="mt-2">
                            <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            <small class="text-muted d-block">الصورة الحالية</small>
                        </div>
                    @endif
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $doctor ? 'حفظ التعديلات' : 'إضافة الطبيب' }}</button>
                <a href="{{ route('dashboard.doctors.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

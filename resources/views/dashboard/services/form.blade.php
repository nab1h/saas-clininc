@extends('dashboard.layout')

@section('title', $service ? 'تعديل الخدمة' : 'إضافة خدمة')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $service ? 'تعديل الخدمة' : 'إضافة خدمة' }}</h1>
        <a href="{{ route('dashboard.services.index') }}" class="btn btn-outline-secondary">← رجوع</a>
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
            <form action="{{ $service ? route('dashboard.services.update', $service) : route('dashboard.services.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @if($service) @method('put') @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $service?->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الاسم (عربي)</label>
                            <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $service?->name_ar) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $service?->description) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">السعر <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price', $service?->price ?? 0) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">المدة (دقيقة) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_minutes" class="form-control" min="1" max="480" value="{{ old('duration_minutes', $service?->duration_minutes ?? 30) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">التصنيف</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $service?->category) }}" placeholder="مثال: كشف، أشعة">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $service?->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">الخدمة نشطة</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">صورة الخدمة</label>
                            @if($service?->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="" class="img-thumbnail" style="max-height: 120px;">
                                    <p class="small text-muted mt-1">الصورة الحالية. رفع صورة جديدة يستبدلها.</p>
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">jpeg, png, gif, webp — حد أقصى 2 ميجا</small>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $service ? 'حفظ التعديلات' : 'إضافة الخدمة' }}</button>
                <a href="{{ route('dashboard.services.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

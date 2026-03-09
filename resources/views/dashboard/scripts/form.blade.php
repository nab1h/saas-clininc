@extends('dashboard.layout')

@section('title', $script ? 'تعديل السكريبت' : 'إضافة سكريبت')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $script ? 'تعديل السكريبت' : 'إضافة سكريبت' }}</h1>
        <a href="{{ route('dashboard.scripts.index') }}" class="btn btn-outline-secondary">← رجوع</a>
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
            <form action="{{ $script ? route('dashboard.scripts.update', $script) : route('dashboard.scripts.store') }}" method="post">
                @csrf
                @if($script) @method('put') @endif

                <div class="mb-3">
                    <label class="form-label">نوع السكريبت <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        @foreach(\App\Models\Script::TYPES as $key => $name)
                            <option value="{{ $key }}" {{ old('type', $script?->type) === $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم السكريبت <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $script?->name) }}" placeholder="مثال: Google Analytics Tracking" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">مكان التثبيت <span class="text-danger">*</span></label>
                    <select name="position" class="form-select" required>
                        @foreach(\App\Models\Script::POSITIONS as $key => $name)
                            <option value="{{ $key }}" {{ old('position', $script?->position) === $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">حدد المكان الذي سيتم فيه وضع السكريبت في الصفحة</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">كود السكريبت <span class="text-danger">*</span></label>
                    <textarea name="code" class="form-control" rows="10" placeholder="<!-- أدخل كود السكريبت هنا -->" required>{{ old('code', $script?->code) }}</textarea>
                    <small class="text-muted">أدخل كود HTML/JavaScript الخاص بالسكريبت</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="order" class="form-control" min="0" value="{{ old('order', $script?->order ?? 0) }}">
                    <small class="text-muted">رقم أقل يظهر أولاً</small>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $script?->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">السكريبت نشط</label>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $script ? 'حفظ التعديلات' : 'إضافة السكريبت' }}</button>
                <a href="{{ route('dashboard.scripts.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

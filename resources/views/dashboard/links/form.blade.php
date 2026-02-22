@extends('dashboard.layout')

@section('title', $link ? 'تعديل الرابط' : 'إضافة رابط')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $link ? 'تعديل الرابط' : 'إضافة رابط' }}</h1>
        <a href="{{ route('dashboard.links.index') }}" class="btn btn-outline-secondary">← رجوع</a>
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
            <form action="{{ $link ? route('dashboard.links.update', $link) : route('dashboard.links.store') }}" method="post">
                @csrf
                @if($link) @method('put') @endif

                <div class="mb-3">
                    <label class="form-label">نوع الرابط <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        @foreach(\App\Models\Link::TYPES as $key => $name)
                            <option value="{{ $key }}" {{ old('type', $link?->type) === $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم مخصص (اختياري)</label>
                    <input type="text" name="label" class="form-control" value="{{ old('label', $link?->label) }}" placeholder="مثال: صفحتنا على فيسبوك">
                </div>

                <div class="mb-3">
                    <label class="form-label">الرابط (URL) <span class="text-danger">*</span></label>
                    <input type="url" name="url" class="form-control" value="{{ old('url', $link?->url) }}" placeholder="https://..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="order" class="form-control" min="0" value="{{ old('order', $link?->order ?? 0) }}">
                    <small class="text-muted">رقم أقل يظهر أولاً</small>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $link?->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">الرابط نشط</label>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $link ? 'حفظ التعديلات' : 'إضافة الرابط' }}</button>
                <a href="{{ route('dashboard.links.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

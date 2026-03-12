@extends('dashboard.layout')

@section('title', $faq ? 'تعديل السؤال' : 'إضافة سؤال')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $faq ? 'تعديل السؤال' : 'إضافة سؤال جديد' }}</h1>
        <a href="{{ route('dashboard.faqs.index') }}" class="btn btn-outline-secondary">← رجوع</a>
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
            <form action="{{ $faq ? route('dashboard.faqs.update', $faq) : route('dashboard.faqs.store') }}" method="post">
                @csrf
                @if($faq) @method('put') @endif

                <div class="mb-3">
                    <label class="form-label">السؤال <span class="text-danger">*</span></label>
                    <input type="text" name="question" class="form-control" value="{{ old('question', $faq?->question) }}" placeholder="مثال: ما هي مواعيد العمل؟" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الإجابة <span class="text-danger">*</span></label>
                    <textarea name="answer" class="form-control" rows="5" placeholder="اكتب الإجابة هنا..." required>{{ old('answer', $faq?->answer) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', $faq?->order ?? 0) }}" min="0">
                            <small class="text-muted">كلما كان الرقم أقل ظهر السؤال أولاً</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $faq?->is_active ?? true) ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('is_active', $faq?->is_active ?? true) ? '' : 'selected' }}>غير نشط</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $faq ? 'حفظ التعديلات' : 'إضافة السؤال' }}</button>
                <a href="{{ route('dashboard.faqs.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

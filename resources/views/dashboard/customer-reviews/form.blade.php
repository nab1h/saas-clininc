@extends('dashboard.layout')

@section('title', $customerReview ? 'تعديل التقييم' : 'إضافة تقييم')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $customerReview ? 'تعديل التقييم' : 'إضافة تقييم' }}</h1>
        <a href="{{ route('dashboard.customer-reviews.index') }}" class="btn btn-outline-secondary">← رجوع</a>
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
            <form action="{{ $customerReview ? route('dashboard.customer-reviews.update', $customerReview) : route('dashboard.customer-reviews.store') }}" method="post">
                @csrf
                @if($customerReview) @method('put') @endif

                <div class="mb-3">
                    <label class="form-label">اسم العميل <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customerReview?->name) }}" placeholder="مثال: محمد أحمد" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">المسمى الوظيفي <span class="text-danger">*</span></label>
                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $customerReview?->job_title) }}" placeholder="مثال: مدير تنفيذي" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">التقييم (1-5 نجوم) <span class="text-danger">*</span></label>
                    <select name="stars" class="form-select" required>
                        <option value="">اختر التقييم</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('stars', $customerReview?->stars) == $i ? 'selected' : '' }}>
                                {{ $i }} {{ $i == 1 ? 'نجمة' : 'نجوم' }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">الرسالة <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control" rows="5" placeholder="اكتب رسالة التقييم هنا..." required>{{ old('message', $customerReview?->message) }}</textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_approved" class="form-check-input" id="is_approved" {{ old('is_approved', $customerReview?->is_approved) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_approved">
                        نشر التقييم (معتمد)
                    </label>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $customerReview ? 'حفظ التعديلات' : 'إضافة التقييم' }}</button>
                <a href="{{ route('dashboard.customer-reviews.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

@extends('dashboard.layout')

@section('title', $user ? 'تعديل المستخدم' : 'إضافة مستخدم')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $user ? 'تعديل المستخدم' : 'إضافة مستخدم' }}</h1>
        <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary">← رجوع</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ $user ? route('dashboard.users.update', $user) : route('dashboard.users.store') }}" method="post">
                @csrf
                @if($user) @method('put') @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user?->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user?->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ $user ? 'كلمة المرور الجديدة (اتركها فارغة للإبقاء على الحالية)' : 'كلمة المرور' }}</label>
                            <input type="password" name="password" class="form-control" {{ $user ? '' : 'required' }} {{ $user ? '' : 'minlength="8"' }}>
                            @if($user)
                                <small class="text-muted">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</small>
                            @else
                                <small class="text-muted">الحد الأدنى 8 أحرف</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">العيادات</h5>
                                @if($user && $user->clinics->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($user->clinics as $clinic)
                                            <li class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <strong>{{ $clinic->name }}</strong>
                                                    <br><small class="text-muted">دور: {{ $clinic->roles->first()?->name ?? 'بدون' }}</small>
                                                </div>
                                                <form action="/dashboard/users/{{ $user->id }}/remove-clinic" method="post" class="d-inline" onsubmit="return confirm('إزالة هذا المستخدم من العيادة؟');">
                                                    @csrf
                                                    <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">لا يوجد عيادات</p>
                                @endif
                                <hr>
                                <a href="#" class="btn btn-sm btn-outline-info w-100">
                                    <i class="fas fa-plus me-1"></i> إضافة لعيادة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $user ? 'حفظ التعديلات' : 'إضافة المستخدم' }}</button>
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>
@endsection

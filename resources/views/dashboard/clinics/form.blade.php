@extends('dashboard.layout')

@section('title', $clinic ? 'تعديل العيادة' : 'إضافة عيادة')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">{{ $clinic ? 'تعديل العيادة' : 'إضافة عيادة' }}</h1>
        <a href="{{ route('dashboard.clinics.index') }}" class="btn btn-outline-secondary">← رجوع</a>
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
            <form action="{{ $clinic ? route('dashboard.clinics.update', $clinic) : route('dashboard.clinics.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @if($clinic) @method('put') @endif

                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label class="form-label">اسم العيادة <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $clinic?->name) }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $clinic?->email) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $clinic?->phone) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $clinic?->address) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">خطة الاشتراك</label>
                                <select name="subscription_plan" class="form-select">
                                    <option value="basic" {{ old('subscription_plan', $clinic?->subscription_plan) === 'basic' ? 'selected' : '' }}>أساسية</option>
                                    <option value="pro" {{ old('subscription_plan', $clinic?->subscription_plan) === 'pro' ? 'selected' : '' }}>متقدمة</option>
                                    <option value="enterprise" {{ old('subscription_plan', $clinic?->subscription_plan) === 'enterprise' ? 'selected' : '' }}>المؤسسات</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاريخ انتهاء التجربة</label>
                                <input type="date" name="trial_ends_at" class="form-control" value="{{ old('trial_ends_at', $clinic?->trial_ends_at?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $clinic?->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">العيادة نشطة</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">شعار العيادة</label>
                            @if($clinic?->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $clinic->logo) }}" alt="" class="img-thumbnail" style="max-height: 120px;">
                                    <p class="small text-muted mt-1">الشعار الحالي. رفع شعار جديد يستبدله.</p>
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted">jpeg, png, gif — حد أقصى 2 ميجا</small>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">{{ $clinic ? 'حفظ التعديلات' : 'إضافة العيادة' }}</button>
                <a href="{{ route('dashboard.clinics.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            </form>
        </div>
    </div>

    @if($clinic)
        <div class="card mt-4">
            <div class="card-header"><i class="fas fa-users me-1"></i> مسؤولو العيادة</div>
            <div class="card-body">
                @if($clinic->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>فرع افتراضي</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clinic->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles->first()?->name ?? '—' }}</td>
                                        <td>{{ $user->pivot->clinic_branch_id ? $clinic->branches->find($user->pivot->clinic_branch_id)?->name ?? '—' : '—' }}</td>
                                        <td>
                                            <form action="{{ route('dashboard.clinics.removeUser', $clinic) }}" method="post" class="d-inline" onsubmit="return confirm('إزالة هذا المستخدم من العيادة؟');">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">إزالة</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">لا يوجد مسؤولون لهذه العيادة.</p>
                @endif
            </div>
        </div>
    @endif
@endsection

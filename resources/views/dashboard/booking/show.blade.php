@extends('dashboard.layout')

@section('title', 'تفاصيل الحجز')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">تفاصيل الحجز #{{ $booking->id }}</h1>
        <a href="{{ route('dashboard.booking.index') }}" class="btn btn-outline-secondary">← رجوع للحجوزات</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">معلومات الحجز</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">المريض</th>
                            <td>{{ $booking->patient->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>الهاتف</th>
                            <td>{{ $booking->patient->phone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>البريد</th>
                            <td>{{ $booking->patient->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>التاريخ</th>
                            <td>{{ $booking->appointment_date?->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>الوقت</th>
                            <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                        </tr>
                        <tr>
                            <th>الخدمة</th>
                            <td>{{ $booking->service?->name_ar ?? $booking->service?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>الحالة</th>
                            <td>
                                @php
                                    $statusLabels = [
                                        'scheduled' => 'مجدول',
                                        'confirmed' => 'مؤكد',
                                        'checked_in' => 'تم الحضور',
                                        'in_progress' => 'قيد التنفيذ',
                                        'completed' => 'منتهي',
                                        'cancelled' => 'ملغي',
                                        'no_show' => 'لم يحضر',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $booking->status === 'cancelled' ? 'danger' : ($booking->status === 'completed' ? 'success' : 'secondary') }}">
                                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                                </span>
                            </td>
                        </tr>
                        @if($booking->notes)
                            <tr>
                                <th>ملاحظات</th>
                                <td>{{ $booking->notes }}</td>
                            </tr>
                        @endif
                        @if($booking->cancel_reason)
                            <tr>
                                <th>سبب الإلغاء</th>
                                <td>{{ $booking->cancel_reason }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">تحديث الحالة</div>
                <div class="card-body">
                    <form action="{{ route('dashboard.booking.updateStatus', $booking) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select" required>
                                <option value="scheduled" {{ $booking->status === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="checked_in" {{ $booking->status === 'checked_in' ? 'selected' : '' }}>تم الحضور</option>
                                <option value="in_progress" {{ $booking->status === 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>منتهي</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                <option value="no_show" {{ $booking->status === 'no_show' ? 'selected' : '' }}>لم يحضر</option>
                            </select>
                        </div>
                        <div class="mb-3" id="cancelReasonWrap" style="{{ $booking->status === 'cancelled' ? '' : 'display:none;' }}">
                            <label class="form-label">سبب الإلغاء</label>
                            <input type="text" name="cancel_reason" class="form-control" value="{{ old('cancel_reason', $booking->cancel_reason) }}" placeholder="اختياري">
                            @error('cancel_reason')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">حفظ التغيير</button>
                    </form>
                    <script>
                        document.querySelector('select[name="status"]').addEventListener('change', function() {
                            document.getElementById('cancelReasonWrap').style.display = this.value === 'cancelled' ? 'block' : 'none';
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection

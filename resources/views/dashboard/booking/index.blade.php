@extends('dashboard.layout')

@section('title', 'الحجوزات')

@section('content')
    <h1 class="mb-4">الحجوزات</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span><i class="fas fa-calendar-check me-1"></i> قائمة الحجوزات</span>
            <form action="{{ route('dashboard.booking.index') }}" method="get" class="d-flex flex-wrap gap-2">
                <select name="status" class="form-select form-select-sm" style="width: auto;">
                    <option value="">كل الحالات</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>منتهي</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
                <input type="date" name="from" class="form-control form-control-sm" style="width: auto;" value="{{ request('from') }}" placeholder="من">
                <input type="date" name="to" class="form-control form-control-sm" style="width: auto;" value="{{ request('to') }}" placeholder="إلى">
                <button type="submit" class="btn btn-primary btn-sm">تصفية</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المريض</th>
                            <th>التاريخ</th>
                            <th>الوقت</th>
                            <th>الخدمة</th>
                            <th>الحالة</th>
                            <th>اتصال</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                            <tr>
                                <td>{{ $b->id }}</td>
                                <td>
                                    {{ $b->patient->name ?? '—' }}
                                    @if($b->patient->phone ?? null)
                                        <br><small class="text-muted">{{ $b->patient->phone }}</small>
                                    @endif
                                </td>
                                <td>{{ $b->appointment_date?->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</td>
                                <td>{{ $b->service?->name_ar ?? $b->service?->name ?? '—' }}</td>
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
                                    <span class="badge bg-{{ $b->status === 'cancelled' ? 'danger' : ($b->status === 'completed' ? 'success' : 'secondary') }}">
                                        {{ $statusLabels[$b->status] ?? $b->status }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $phone = $b->patient->phone ?? null;
                                        $phoneDigits = $phone ? preg_replace('/\D/', '', $phone) : '';
                                        $waNumber = $phoneDigits ? (str_starts_with($phoneDigits, '20') ? $phoneDigits : '20' . ltrim($phoneDigits, '0')) : '';
                                    @endphp
                                    @if($waNumber)
                                        <a href="tel:{{ $phone }}" class="btn btn-sm btn-success me-1" title="اتصال"><i class="fas fa-phone-alt"></i></a>
                                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="btn btn-sm btn-success" style="background:#25D366; border-color:#25D366;" title="واتساب"><i class="fab fa-whatsapp"></i></a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dashboard.booking.show', $b) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> عرض
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.booking.destroy', $b) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموعد؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">لا توجد حجوزات.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
@endsection

@extends('dashboard.layout')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">لوحة تحكم العيادة</h1>
                <p class="text-muted mb-0 small">نظرة عامة على العيادة والإحصائيات</p>
            </div>
            <div class="text-muted small">
                <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- بطاقات الإحصائيات الرئيسية -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small">إجمالي العيادات</div>
                                <div class="h3 mb-0">{{ $totalClinics }}</div>
                            </div>
                            <i class="fas fa-hospital fa-2x opacity-50"></i>
                        </div>
                        <div class="mt-2 small">
                            <span class="text-white-75">{{ $activeClinics }} نشطة</span>
                            <span class="text-white-50 ms-2">{{ $totalClinics - $activeClinics }} غير نشطة</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-transparent border-0">
                        <a class="small text-white stretched-link" href="#clinics-section">عرض التفاصيل</a>
                        <div class="small text-white"><i class="fas fa-angle-left"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small">إجمالي المستخدمين</div>
                                <div class="h3 mb-0">{{ $totalUsers }}</div>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                        <div class="mt-2 small text-white-75">
                            <i class="fas fa-user-md me-1"></i> مديرين وموظفين
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-transparent border-0">
                        <a class="small text-white stretched-link" href="#users-section">عرض التفاصيل</a>
                        <div class="small text-white"><i class="fas fa-angle-left"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small">إجمالي المواعيد</div>
                                <div class="h3 mb-0">{{ $totalAppointments }}</div>
                            </div>
                            <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                        </div>
                        <div class="mt-2 small">
                            <span class="text-white-75">{{ $thisMonthAppointments }} هذا الشهر</span>
                            <span class="ms-2 @if($appointmentsGrowth >= 0) text-success @else text-danger @endif">
                                <i class="fas fa-arrow-@if($appointmentsGrowth >= 0) up @else down @endif me-1"></i>
                                {{ number_format(abs($appointmentsGrowth), 1) }}%
                            </span>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-transparent border-0">
                        <a class="small text-white stretched-link" href="{{ route('dashboard.appointments.index') }}">عرض المواعيد</a>
                        <div class="small text-white"><i class="fas fa-angle-left"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small">إجمالي المرضى</div>
                                <div class="h3 mb-0">{{ $totalPatients }}</div>
                            </div>
                            <i class="fas fa-user-injured fa-2x opacity-50"></i>
                        </div>
                        <div class="mt-2 small text-white-75">
                            <i class="fas fa-heartbeat me-1"></i> مراجعين مسجلين
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between bg-transparent border-0">
                        <a class="small text-white stretched-link" href="{{ route('dashboard.patients.index') }}">عرض المرضى</a>
                        <div class="small text-white"><i class="fas fa-angle-left"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات إضافية -->
        <div class="row g-4 mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-concierge-bell me-2"></i>الخدمات</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="h2 mb-0 text-primary">{{ $totalServices }}</div>
                        <small class="text-muted">خدمة مسجلة</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('dashboard.services.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-newspaper me-2"></i>المقالات</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="h2 mb-0 text-success">{{ $publishedArticles }}</div>
                        <small class="text-muted">منشورة من أصل {{ $totalArticles }} مقال</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('dashboard.articles.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>الفواتير</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="h2 mb-0 text-info">{{ number_format($totalRevenue, 2) }}</div>
                        <small class="text-muted">الإيرادات ({{ $paidInvoices }} مدفوعة)</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('dashboard.invoices.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- حالة المواعيد -->
        <div class="row g-4 mb-4" id="appointments-section">
            <div class="col-xl-3 col-md-6">
                <div class="card border-warning h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2"><i class="fas fa-calendar-alt fa-2x"></i></div>
                        <div class="h3 mb-0">{{ $pendingAppointments }}</div>
                        <small class="text-muted">جدولة</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-success h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2"><i class="fas fa-check-circle fa-2x"></i></div>
                        <div class="h3 mb-0">{{ $confirmedAppointments }}</div>
                        <small class="text-muted">مؤكدة</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-info h-100">
                    <div class="card-body text-center">
                        <div class="text-info mb-2"><i class="fas fa-check-double fa-2x"></i></div>
                        <div class="h3 mb-0">{{ $completedAppointments }}</div>
                        <small class="text-muted">مكتملة</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-danger h-100">
                    <div class="card-body text-center">
                        <div class="text-danger mb-2"><i class="fas fa-times-circle fa-2x"></i></div>
                        <div class="h3 mb-0">{{ $cancelledAppointments }}</div>
                        <small class="text-muted">ملغاة</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- المواعيد القادمة -->
        @if($upcomingAppointments->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-calendar-day me-2"></i>المواعيد القادمة</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>المريض</th>
                                <th>الخدمة</th>
                                <th>العيادة</th>
                                <th>التاريخ</th>
                                <th>الوقت</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient?->name ?? '—' }}</td>
                                <td>{{ $appointment->service?->name ?? '—' }}</td>
                                <td>{{ $appointment->clinic?->name ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</td>
                                <td>{{ $appointment->start_time ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'scheduled' ? 'warning' : 'secondary') }}">
                                        @if($appointment->status === 'confirmed') مؤكد
                                        @elseif($appointment->status === 'scheduled') جدولة
                                        @else {{ $appointment->status }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- العيادات النشطة مؤخراً -->
        <div class="row g-4 mb-4" id="clinics-section">
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-hospital-alt me-2"></i>العيادات النشطة مؤخراً</h6>
                    </div>
                    <div class="card-body">
                        @if($recentClinics->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>اسم العيادة</th>
                                        <th>الخطة</th>
                                        <th>تاريخ الإضافة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentClinics as $clinic)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary me-1">نشط</span>
                                            {{ $clinic->name }}
                                        </td>
                                        <td>
                                            @if($clinic->subscription_plan)
                                                <span class="badge bg-info">{{ $clinic->subscription_plan }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $clinic->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center mb-0">لا توجد عيادات نشطة بعد</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- المقالات الأخيرة -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-rss me-2"></i>المقالات الأخيرة</h6>
                    </div>
                    <div class="card-body">
                        @if($recentArticles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>العنوان</th>
                                        <th>الكاتب</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentArticles as $article)
                                    <tr>
                                        <td>
                                            @if($article->is_favorite)
                                                <i class="fas fa-star text-warning me-1"></i>
                                            @endif
                                            <a href="{{ route('dashboard.articles.show', $article) }}" class="text-decoration-none">
                                                {{ Str::limit($article->title, 40) }}
                                            </a>
                                        </td>
                                        <td>{{ $article->user?->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $article->is_published ? 'success' : 'secondary' }}">
                                                {{ $article->is_published ? 'منشور' : 'مسودة' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center mb-0">لا توجد مقالات بعد</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- مخطط المواعيد لآخر 7 أيام -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>المواعيد خلال آخر 7 أيام</h6>
            </div>
            <div class="card-body">
                <canvas id="appointmentsChart" height="100"></canvas>
            </div>
        </div>

        <!-- إحصائيات العيادات حسب الاشتراك -->
        <div class="row g-4" id="users-section">
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-tags me-2"></i>العيادات حسب خطط الاشتراك</h6>
                    </div>
                    <div class="card-body">
                        @if($clinicsByPlan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>خطة الاشتراك</th>
                                        <th class="text-center">عدد العيادات</th>
                                        <th class="text-center">النسبة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clinicsByPlan as $plan => $count)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $plan ?: 'بدون خطة' }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $count }}</td>
                                        <td class="text-center">
                                            {{ $totalClinics > 0 ? number_format(($count / $totalClinics) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted text-center mb-0">لا توجد بيانات خطط اشتراك</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ملخص سريع -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>ملخص سريع</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="h4 mb-1 text-primary">{{ $draftArticles }}</div>
                                    <small class="text-muted">مقالات مسودة</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="h4 mb-1 text-warning">{{ $favoriteArticles }}</div>
                                    <small class="text-muted">مقالات مفضلة</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="h4 mb-1 text-success">{{ $pendingInvoices }}</div>
                                    <small class="text-muted">فواتير معلقة</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <div class="h4 mb-1 text-info">{{ $totalInvoices }}</div>
                                    <small class="text-muted">إجمالي الفواتير</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('appointmentsChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [{!! collect($last7Days)->pluck('label')->map(fn($l) => "'$l'")->implode(',') !!}],
                        datasets: [{
                            label: 'المواعيد',
                            data: [{!! collect($last7Days)->pluck('count')->implode(',') !!}],
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection

@extends('dashboard.layout')

@section('title', 'العيادات')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">العيادات</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClinicModal">
            <i class="fas fa-plus me-1"></i> إضافة عيادة
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-hospital me-1"></i> قائمة العيادات</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">الشعار</th>
                            <th>الاسم</th>
                            <th>Slug</th>
                            <th>البريد الإلكتروني</th>
                            <th>الهاتف</th>
                            <th>المسؤولون</th>
                            <th>الفروع</th>
                            <th>الحالة</th>
                            <th style="width: 200px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clinics as $c)
                            <tr>
                                <td>
                                    @if($c->logo)
                                        <img src="{{ asset('storage/' . $c->logo) }}" alt="" class="rounded" style="width: 56px; height: 56px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 56px; height: 56px;">
                                            <i class="fas fa-hospital fa-lg"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $c->name }}</strong>
                                    @if($c->address)
                                        <br><small class="text-muted">{{ $c->address }}</small>
                                    @endif
                                </td>
                                <td><code class="small">{{ $c->slug }}</code></td>
                                <td>{{ $c->email }}</td>
                                <td>{{ $c->phone ?? '—' }}</td>
                                <td>
                                    <small class="text-muted">
                                        @if($c->users->count() > 0)
                                            {{ $c->users->pluck('name')->join('، ') }}
                                        @else
                                            <span class="text-danger">لا يوجد مسؤولون</span>
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $c->branches->count() }}</td>
                                <td>
                                    <span class="badge bg-{{ $c->is_active ? 'success' : 'secondary' }}">
                                        {{ $c->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                    @if($c->trial_ends_at && $c->trial_ends_at->isFuture())
                                        <br><small class="text-warning">تجربة تنتهي: {{ $c->trial_ends_at->format('Y-m-d') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dashboard.clinics.edit', $c) }}" class="btn btn-outline-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-{{ $c->is_active ? 'warning' : 'success' }}" title="{{ $c->is_active ? 'تعطيل' : 'تفعيل' }}" onclick="toggleStatus('{{ $c->slug }}')">
                                            <i class="fas fa-{{ $c->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-info" title="إدارة المسؤولين" data-bs-toggle="modal" data-bs-target="#manageUsersModal" onclick="loadClinicUsers('{{ $c->slug }}', '{{ $c->name }}')">
                                            <i class="fas fa-users"></i>
                                        </button>
                                        <form action="{{ route('dashboard.clinics.destroy', $c) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذه العيادة؟ هذا الإجراء لا يمكن التراجع عنه.');">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">لا توجد عيادات.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Clinic Modal -->
    <div class="modal fade" id="addClinicModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة عيادة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.clinics.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label class="form-label">اسم العيادة <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">العنوان</label>
                                    <textarea name="address" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">خطة الاشتراك</label>
                                        <select name="subscription_plan" class="form-select">
                                            <option value="basic">أساسية</option>
                                            <option value="pro">متقدمة</option>
                                            <option value="enterprise">المؤسسات</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">تاريخ انتهاء التجربة</label>
                                        <input type="date" name="trial_ends_at" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">تعيين مسؤول</label>
                                        <select name="manager_id" class="form-select">
                                            <option value="">بدون</option>
                                            @foreach($users ?? [] as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">دور المسؤول</label>
                                        <select name="manager_role_id" class="form-select">
                                            <option value="">اختر الدور</option>
                                            @foreach($roles ?? [] as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">العيادة نشطة</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">شعار العيادة</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <small class="text-muted">jpeg, png, gif — حد أقصى 2 ميجا</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة العيادة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manage Users Modal -->
    <div class="modal fade" id="manageUsersModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إدارة مسؤولي العيادة: <span id="clinicName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="assignUserForm" action="#" method="post">
                        @csrf
                        <input type="hidden" name="clinic_id" id="clinicId">
                        <div class="mb-3">
                            <label class="form-label">إضافة مسؤول جديد</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="user_id" class="form-select" required>
                                        <option value="">اختر المستخدم</option>
                                        @foreach($users ?? [] as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="role_id" class="form-select" required>
                                        <option value="">اختر الدور</option>
                                        @foreach($roles ?? [] as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">إضافة</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h6>المسؤولون الحاليون:</h6>
                    <div id="clinicUsers" class="list-group list-group-flush">
                        <!-- Users will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentClinicId = null;
        let clinicUsers = {};

        function toggleStatus(id) {
            if(confirm('هل تريد تغيير حالة العيادة؟')) {
                fetch(`/dashboard/clinics/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams({'_method': 'POST'})
                }).then(() => location.reload());
            }
        }

        function loadClinicUsers(clinicId, clinicName) {
            currentClinicId = clinicId;
            document.getElementById('clinicName').textContent = clinicName;
            document.getElementById('clinicId').value = clinicId;
            document.getElementById('assignUserForm').action = `/dashboard/clinics/${clinicId}/assign`;

            // Load users
            fetch(`/api/clinics/${clinicId}/users`)
                .then(r => r.json())
                .then(data => {
                    clinicUsers = data.users || [];
                    renderClinicUsers();
                });
        }

        function renderClinicUsers() {
            const container = document.getElementById('clinicUsers');
            if (clinicUsers.length === 0) {
                container.innerHTML = '<p class="text-muted">لا يوجد مسؤولون</p>';
                return;
            }

            container.innerHTML = clinicUsers.map(u => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${u.name}</strong>
                        <br><small class="text-muted">${u.email} - ${u.role_name || 'بدون دور'}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUser(${u.id})">
                        <i class="fas fa-times"></i> إزالة
                    </button>
                </div>
            `).join('');
        }

        function removeUser(userId) {
            if(confirm('هل تريد إزالة هذا المستخدم من العيادة؟')) {
                fetch(`/dashboard/clinics/${currentClinicId}/remove-user`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}&_method=POST`
                }).then(() => location.reload());
            }
        }

        // Handle assign user form submission
        document.getElementById('assignUserForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            }).then(() => location.reload());
        });
    </script>
@endsection

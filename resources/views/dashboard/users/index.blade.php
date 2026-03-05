@extends('dashboard.layout')

@section('title', 'المستخدمين')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="mb-0">المستخدمين</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus me-1"></i> إضافة مستخدم
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
        <div class="card-header"><i class="fas fa-users me-1"></i> قائمة المستخدمين</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>العيادات</th>
                            <th>المواعيد</th>
                            <th>المقالات</th>
                            <th>تاريخ الإنشاء</th>
                            <th style="width: 180px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td><span class="badge bg-secondary">#{{ $u->id }}</span></td>
                                <td><strong>{{ $u->name }}</strong></td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $u->clinics->count() }}</span>
                                    @if($u->clinics->count() > 0)
                                        <small class="text-muted">{{ $u->clinics->pluck('name')->join('، ') }}</small>
                                    @endif
                                </td>
                                <td>{{ $u->appointments->count() }}</td>
                                <td>{{ $u->articles->count() }}</td>
                                <td><small class="text-muted">{{ $u->created_at->format('Y-m-d H:i') }}</small></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dashboard.users.edit', $u) }}" class="btn btn-outline-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-info" title="تعيين لعيادة" data-bs-toggle="modal" data-bs-target="#assignClinicModal" onclick="loadUserForClinic({{ $u->id }}, '{{ $u->name }}')">
                                            <i class="fas fa-hospital"></i>
                                        </button>
                                        <form action="{{ route('dashboard.users.destroy', $u) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا المستخدم؟');">
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
                                <td colspan="8" class="text-center text-muted py-4">لا يوجد مستخدمين.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة مستخدم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dashboard.users.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">الاسم <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الدور</label>
                                <select name="role_id" class="form-select">
                                    <option value="">بدون</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">العيادة</label>
                                <select name="clinic_id" class="form-select">
                                    <option value="">بدون</option>
                                    @foreach(\App\Models\Clinic::all() as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <small class="text-muted">* يمكنك تعيين المستخدم لعيادة لاحقاً</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة المستخدم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign to Clinic Modal -->
    <div class="modal fade" id="assignClinicModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعيين المستخدم للعيادة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/dashboard/users/{{ $users->first()?->id ?? 0 }}/assign-clinic" method="post" id="assignClinicForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="assignUserId" value="">
                        <input type="hidden" name="clinic_id" id="assignClinicId" value="">
                        <input type="hidden" name="role_id" id="assignRoleId" value="">

                        <p>تعيين <strong id="assignUserName"></strong> لعيادة:</p>

                        <div class="mb-3">
                            <label class="form-label">العيادة <span class="text-danger">*</span></label>
                            <select name="clinic_id" class="form-select" id="clinicSelect" required>
                                <option value="">اختر العيادة</option>
                                @foreach(\App\Models\Clinic::all() as $clinic)
                                    <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الدور <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select" id="roleSelect" required>
                                <option value="">اختر الدور</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr>
                        <h6>العيادات الحالية:</h6>
                        <div id="userClinicsList">
                            <!-- Will be loaded dynamically -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">تعيين</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentUserId = null;
        let currentUserName = null;
        let userClinics = {};

        function loadUserForClinic(userId, userName) {
            currentUserId = userId;
            currentUserName = userName;
            document.getElementById('assignUserName').textContent = userName;

            // Load user's clinics
            fetch(`/api/users/${userId}/clinics`)
                .then(r => r.json())
                .then(data => {
                    userClinics = data.clinics || [];
                    renderUserClinics();
                });
        }

        function renderUserClinics() {
            const container = document.getElementById('userClinicsList');
            if (userClinics.length === 0) {
                container.innerHTML = '<p class="text-muted">لا يوجد عيادات</p>';
                return;
            }

            container.innerHTML = userClinics.map(c => `
                <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2">
                    <div>
                        <strong>${c.name}</strong>
                        <br><small class="text-muted">دور: ${c.role_name || 'بدون'}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeClinic(${c.clinic_id})">
                        <i class="fas fa-times"></i> إزالة
                    </button>
                </div>
            `).join('');
        }

        function removeClinic(clinicId) {
            const userId = currentUserId;
            fetch(`/dashboard/users/${userId}/remove-clinic`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `clinic_id=${clinicId}&_method=POST`
            }).then(() => {
                loadUserForClinic(userId, currentUserName);
            });
        }

        // Handle assign clinic form submission
        document.getElementById('assignClinicForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = currentUserId;
            const clinicId = document.getElementById('clinicSelect').value;
            const roleId = document.getElementById('roleSelect').value;

            fetch(`/dashboard/users/${userId}/assign-clinic`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: new URLSearchParams({
                    clinic_id: clinicId,
                    role_id: roleId
                })
            }).then(() => {
                location.reload();
            });
        });

        // Update form action when user is selected
        document.querySelectorAll('[data-bs-target="#assignClinicModal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('onclick').match(/loadUserForClinic\((\d+)/)[1];
                document.getElementById('assignClinicForm').action = `/dashboard/users/${userId}/assign-clinic`;
            });
        });
    </script>
@endsection

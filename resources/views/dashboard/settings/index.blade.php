@extends('dashboard.layout')

@section('title', 'إعدادات العيادة')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
        <div class="d-block mb-4 mb-md-0">
            <h1 class="h4 mb-0">الإعدادات</h1>
            <nav class="d-none d-md-inline-block" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">الإعدادات</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- معلومات العيادة -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building me-2"></i>
                        معلومات العيادة
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.settings.update') }}" method="POST" id="basic-info-form">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">اسم العيادة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                   value="{{ old('name', $clinic->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email', $clinic->email) }}">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone', $clinic->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $clinic->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="google_maps" class="form-label">Google Maps <i class="fas fa-map-marker-alt ms-1 text-muted"></i></label>
                            <input type="text" class="form-control" id="google_maps" name="google_maps"
                                   value="{{ old('google_maps', $clinic->settings['google_maps'] ?? '') }}"
                                   placeholder="رابط Google Maps أو كود الموقع">
                            <small class="text-muted">الصق رابط Google Maps من متصفحك</small>
                        </div>

                        <div class="mb-3">
                            <label for="working_hours" class="form-label">ساعات العمل <i class="fas fa-clock ms-1 text-muted"></i></label>
                            <input type="text" class="form-control" id="working_hours" name="working_hours"
                                   value="{{ old('working_hours', $clinic->settings['working_hours'] ?? '') }}"
                                   placeholder="مثال: السبت - الخميس: 9 صباحاً - 5 مساءً">
                        </div>

                        <div class="mt-4">
                            <button type="submit" formaction="{{ route('dashboard.settings.update') }}" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                حفظ المعلومات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- اللوجو والأيقونات -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>
                        اللوجو والأيقونات
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.settings.update') }}" method="POST" id="icons-form" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="logo" class="form-label">شعار العيادة (Logo)</label>
                            @if($clinic->logo)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->logo) }}" alt="Logo" class="img-thumbnail" style="max-height: 80px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_logo').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Favicon (أيقونة المتصفح)</label>
                            @if($clinic->settings['favicon'] ?? null)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->settings['favicon']) }}" alt="Favicon" class="img-thumbnail" style="width: 32px; height: 32px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_favicon').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="favicon" name="favicon" accept=".ico,.png,.jpg,.jpeg">
                            <input type="hidden" name="remove_favicon" id="remove_favicon" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">أيقونة 16×16</label>
                            @if($clinic->settings['icon_16'] ?? null)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->settings['icon_16']) }}" alt="Icon 16x16" class="img-thumbnail" style="width: 16px; height: 16px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_icon_16').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="icon_16" name="icon_16" accept="image/*">
                            <input type="hidden" name="remove_icon_16" id="remove_icon_16" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">أيقونة 32×32</label>
                            @if($clinic->settings['icon_32'] ?? null)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->settings['icon_32']) }}" alt="Icon 32x32" class="img-thumbnail" style="width: 32px; height: 32px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_icon_32').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="icon_32" name="icon_32" accept="image/*">
                            <input type="hidden" name="remove_icon_32" id="remove_icon_32" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">أيقونة 48×48</label>
                            @if($clinic->settings['icon_48'] ?? null)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->settings['icon_48']) }}" alt="Icon 48x48" class="img-thumbnail" style="width: 48px; height: 48px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_icon_48').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="icon_48" name="icon_48" accept="image/*">
                            <input type="hidden" name="remove_icon_48" id="remove_icon_48" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">أيقونة 180×180 (iPhone/iPad)</label>
                            @if($clinic->settings['icon_180'] ?? null)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->settings['icon_180']) }}" alt="Icon 180x180" class="img-thumbnail" style="width: 60px; height: 60px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_icon_180').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="icon_180" name="icon_180" accept="image/*">
                            <input type="hidden" name="remove_icon_180" id="remove_icon_180" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">أيقونة 192×192 (Android)</label>
                            @if($clinic->settings['icon_192'] ?? null)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->settings['icon_192']) }}" alt="Icon 192x192" class="img-thumbnail" style="width: 48px; height: 48px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_icon_192').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="icon_192" name="icon_192" accept="image/*">
                            <input type="hidden" name="remove_icon_192" id="remove_icon_192" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">أيقونة 512×512 (Windows)</label>
                            @if($clinic->settings['icon_512'] ?? null)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $clinic->settings['icon_512']) }}" alt="Icon 512x512" class="img-thumbnail" style="width: 48px; height: 48px;">
                                    <a href="javascript:void(0)" onclick="document.getElementById('remove_icon_512').value='1'; document.getElementById('icons-form').submit();" class="text-danger btn-sm">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="icon_512" name="icon_512" accept="image/*">
                            <input type="hidden" name="remove_icon_512" id="remove_icon_512" value="0">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                حفظ الأيقونات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- صور الخلفية والمحيط -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>
                        صور الخلفية والمحيط
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.settings.update') }}" method="POST" id="images-form" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="background_image" class="form-label">صورة الخلفية</label>
                            @if($clinic->background_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $clinic->background_image) }}" alt="Background Image" class="img-thumbnail w-100" style="max-height: 200px; object-fit: cover;">
                                    <div class="mt-2">
                                        <a href="javascript:void(0)" onclick="document.getElementById('remove_background_image').value='1'; document.getElementById('images-form').submit();" class="text-danger btn-sm">
                                            <i class="fas fa-trash"></i> حذف
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="background_image" name="background_image" accept="image/*">
                            <input type="hidden" name="remove_background_image" id="remove_background_image" value="0">
                            <small class="text-muted">صورة الخلفية للموقع (يتم عرضها في الخلفية)</small>
                        </div>

                        <div class="mb-3">
                            <label for="surrounding_image" class="form-label">صورة المحيط</label>
                            @if($clinic->surrounding_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $clinic->surrounding_image) }}" alt="Surrounding Image" class="img-thumbnail w-100" style="max-height: 200px; object-fit: cover;">
                                    <div class="mt-2">
                                        <a href="javascript:void(0)" onclick="document.getElementById('remove_surrounding_image').value='1'; document.getElementById('images-form').submit();" class="text-danger btn-sm">
                                            <i class="fas fa-trash"></i> حذف
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="surrounding_image" name="surrounding_image" accept="image/*">
                            <input type="hidden" name="remove_surrounding_image" id="remove_surrounding_image" value="0">
                            <small class="text-muted">صورة المحيط (يتم عرضها في أعلى الصفحة أو الهيدر)</small>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                حفظ الصور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- معلومات الإعدادات -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>
                        معلومات الإعدادات
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.settings.update') }}" method="POST" id="settings-form" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="content" class="form-label">المحتوى</label>
                            <textarea class="form-control" id="content" name="content" rows="4" placeholder="محتوى صفحة الترحيب أو تعليمات عامة">{{ old('content', $clinic->settings['content'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">رسالة الترحيب</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="رسالة ترحيب في صفحة الداشبورد">{{ old('message', $clinic->settings['message'] ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="footer_text" class="form-label">نص الفوتر</label>
                            <textarea class="form-control" id="footer_text" name="footer_text" rows="3" placeholder="نص يظهر في أسفل جميع الصفحات">{{ old('footer_text', $clinic->settings['footer_text'] ?? '') }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="brand_color" class="form-label">اللون التجاري</label>
                                <input type="color" class="form-control form-control-color" id="brand_color" name="brand_color"
                                       value="{{ old('brand_color', $clinic->settings['brand_color'] ?? '#667eea') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="primary_color" class="form-label">اللون الأساسي</label>
                                <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color"
                                       value="{{ old('primary_color', $clinic->settings['primary_color'] ?? '#764ba2') }}">
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                حفظ الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('dashboard.layout')

@section('title', 'إعدادات العيادة')

@section('content')
    <h1 class="mb-4">إعدادات العيادة</h1>

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

    <form action="{{ route('dashboard.settings.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        @php
            $settings = $clinic->settings ?? [];
        @endphp

        <div class="card mb-4">
            <div class="card-header">الاسم واللوجو</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">اسم العيادة <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $clinic->name) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">لوجو العيادة</label>
                        @if($clinic->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $clinic->logo) }}" alt="لوجو" class="img-thumbnail" style="max-height: 80px;">
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">المحتويات ورسالة العيادة</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">المحتويات / الوصف</label>
                    <textarea name="content" class="form-control" rows="4" placeholder="نص تعريفي أو محتوى الصفحة الرئيسية">{{ old('content', $settings['content'] ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">رسالة العيادة</label>
                    <textarea name="message" class="form-control" rows="3" placeholder="رسالة ترحيب أو تنبيه للزوار">{{ old('message', $settings['message'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">أيقونات التاب (Favicon) بجميع الأحجام</div>
            <div class="card-body">
                <p class="text-muted small">رفع أيقونات تظهر بجانب عنوان الصفحة في المتصفح. الأحجام الشائعة: 16×16، 32×32، 48×48، 180×180 (آبل)، 192×192، 512×512.</p>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">Favicon (32×32)</label>
                        @if(!empty($settings['favicon']))
                            <div class="mb-1"><img src="{{ asset('storage/' . $settings['favicon']) }}" alt="" style="width:32px;height:32px;object-fit:contain;"></div>
                        @endif
                        <input type="file" name="favicon" class="form-control form-control-sm" accept=".ico,.png,.jpg,.jpeg">
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">16×16</label>
                        @if(!empty($settings['icon_16']))
                            <div class="mb-1"><img src="{{ asset('storage/' . $settings['icon_16']) }}" alt="" style="width:16px;height:16px;object-fit:contain;"></div>
                        @endif
                        <input type="file" name="icon_16" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">32×32</label>
                        @if(!empty($settings['icon_32']))
                            <div class="mb-1"><img src="{{ asset('storage/' . $settings['icon_32']) }}" alt="" style="width:32px;height:32px;object-fit:contain;"></div>
                        @endif
                        <input type="file" name="icon_32" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">48×48</label>
                        @if(!empty($settings['icon_48']))
                            <div class="mb-1"><img src="{{ asset('storage/' . $settings['icon_48']) }}" alt="" style="width:48px;height:48px;object-fit:contain;"></div>
                        @endif
                        <input type="file" name="icon_48" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">180×180 (Apple Touch)</label>
                        @if(!empty($settings['icon_180']))
                            <div class="mb-1"><img src="{{ asset('storage/' . $settings['icon_180']) }}" alt="" style="width:60px;height:60px;object-fit:contain;"></div>
                        @endif
                        <input type="file" name="icon_180" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">192×192</label>
                        @if(!empty($settings['icon_192']))
                            <div class="mb-1"><img src="{{ asset('storage/' . $settings['icon_192']) }}" alt="" style="width:48px;height:48px;object-fit:contain;"></div>
                        @endif
                        <input type="file" name="icon_192" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label">512×512</label>
                        @if(!empty($settings['icon_512']))
                            <div class="mb-1"><img src="{{ asset('storage/' . $settings['icon_512']) }}" alt="" style="width:48px;height:48px;object-fit:contain;"></div>
                        @endif
                        <input type="file" name="icon_512" class="form-control form-control-sm" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
    </form>
@endsection

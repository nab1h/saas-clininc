<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>حجز موعد - {{ $clinic->name ?? config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; color: #333; }
        .container { max-width: 520px; margin: 0 auto; background: #fff; padding: 28px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        h1 { margin: 0 0 24px; font-size: 1.5rem; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #0d6efd; }
        .text-danger { color: #dc3545; font-size: 0.875rem; margin-top: 4px; }
        button[type="submit"] { width: 100%; padding: 12px; background: #0d6efd; color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        button[type="submit"]:hover { background: #0b5ed7; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; }
        .alert-error { background: #f8d7da; color: #842029; }
        .back { display: inline-block; margin-bottom: 16px; color: #0d6efd; text-decoration: none; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/') }}" class="back">← الرئيسية</a>

        <h1>حجز موعد</h1>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('booking.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="name">الاسم <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="phone">رقم الهاتف <span class="text-danger">*</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
                @error('phone') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="appointment_date">تاريخ الموعد <span class="text-danger">*</span></label>
                <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required>
                @error('appointment_date') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="start_time">وقت الموعد <span class="text-danger">*</span></label>
                <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
                @error('start_time') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            @if($services->isNotEmpty())
            <div class="form-group">
                <label for="service_id">الخدمة</label>
                <select name="service_id" id="service_id">
                    <option value="">— اختر الخدمة —</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name_ar ?? $s->name }}
                        </option>
                    @endforeach
                </select>
                @error('service_id') <p class="text-danger">{{ $message }}</p> @enderror
            </div>
            @endif

            <div class="form-group">
                <label for="notes">ملاحظات</label>
                <textarea name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-danger">{{ $message }}</p> @enderror
            </div>

            <button type="submit">تأكيد الحجز</button>
        </form>
    </div>
</body>
</html>

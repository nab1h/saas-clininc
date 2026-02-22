<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تم الحجز بنجاح - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; color: #333; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .box { max-width: 420px; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); text-align: center; }
        h1 { margin: 0 0 12px; font-size: 1.5rem; color: #198754; }
        p { margin: 0 0 24px; color: #666; }
        a { display: inline-block; padding: 10px 20px; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 6px; }
        a:hover { background: #0b5ed7; }
    </style>
</head>
<body>
    <div class="box">
        <h1>تم تسجيل حجزك بنجاح</h1>
        <p>{{ session('success', 'سنتواصل معك قريباً لتأكيد الموعد.') }}</p>
        <a href="{{ route('booking.create') }}">حجز موعد آخر</a>
        &nbsp;
        <a href="{{ url('/') }}" style="background:#6c757d;">الرئيسية</a>
    </div>
</body>
</html>

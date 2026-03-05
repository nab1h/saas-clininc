<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار التسجيل</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container">
        <h1>صفحة اختبار التسجيل</h1>
        <div class="alert alert-info">
            هذه صفحة اختبار للتأكد من أن التسجيل يعمل بشكل صحيح.
        </div>

        <div id="message"></div>

        <form id="testForm" class="card p-4">
            <div class="mb-3">
                <label>الاسم</label>
                <input type="text" name="name" class="form-control" value="Test User {{ time() }}" required>
            </div>
            <div class="mb-3">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="test{{ time() }}@example.com" required>
            </div>
            <div class="mb-3">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control" value="password123" required>
            </div>
            <div class="mb-3">
                <label>تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" value="password123" required>
            </div>
            <button type="submit" class="btn btn-primary">تسجيل الحساب</button>
        </form>

        <div class="mt-3">
            <a href="/register" class="btn btn-outline-primary">العودة لصفحة التسجيل الأصلية</a>
            <a href="/login" class="btn btn-outline-secondary">العودة لصفحة تسجيل الدخول</a>
        </div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');

            messageDiv.innerHTML = '<div class="alert alert-info">جاري التسجيل...</div>';

            fetch('/register', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    messageDiv.innerHTML = '<div class="alert alert-success">تم التسجيل بنجاح! جاري التحويل...</div>';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                }
            })
            .catch(error => {
                messageDiv.innerHTML = '<div class="alert alert-danger">حدث خطأ: ' + error.message + '</div>';
            });
        });
    </script>
</body>
</html>

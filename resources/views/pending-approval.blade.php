<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بانتظار الموافقة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .waiting-card {
            border: none;
            border-radius: 20px;
            background: white;
        }
        .waiting-icon {
            font-size: 5rem;
            color: #f39c12;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="waiting-card p-5 text-center shadow">
                    <div class="mb-4">
                        <i class="fas fa-clock waiting-icon"></i>
                    </div>
                    <h2 class="mb-3">حسابك قيد المراجعة</h2>
                    <p class="text-muted mb-4">
                        شكراً لك على التسجيل في منصتنا!<br>
                        حسابك حالياً قيد المراجعة من قبل الإدارة.<br>
                        سنقوم بإعلامك عبر البريد الإلكتروني عند الموافقة على حسابك.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        عادةً ما تستغرق عملية المراجعة من 24 إلى 48 ساعة
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary mt-3">
                        <i class="fas fa-arrow-left me-2"></i>
                        رجوع لصفحة تسجيل الدخول
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

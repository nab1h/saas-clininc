<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .pricing-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: white;
        }
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .pricing-card.featured {
            border: 3px solid #667eea;
            transform: scale(1.05);
        }
        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }
        .price {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
        .price span {
            font-size: 1rem;
            color: #999;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list i {
            color: #28a745;
            margin-left: 10px;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .badge-popular {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 text-white mb-3">اختر الباقة المناسبة لك</h1>
            <p class="lead text-white-50">ابدأ رحلتك معنا واختر الباقة التي تناسب احتياجاتك</p>
        </div>

        <div class="row g-4 align-items-center">
            <!-- الباقة الأساسية -->
            <div class="col-md-4">
                <div class="pricing-card p-4 text-center position-relative">
                    <div class="mb-4">
                        <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                        <h3>الباقة الأساسية</h3>
                        <p class="text-muted">للمبتدئين والعيادات الصغيرة</p>
                    </div>
                    <div class="price mb-4">
                        99<span>ج.م/شهر</span>
                    </div>
                    <ul class="feature-list text-start mb-4">
                        <li><i class="fas fa-check"></i> 3 أطباء</li>
                        <li><i class="fas fa-check"></i> 50 مريض</li>
                        <li><i class="fas fa-check"></i> 10 مواعيد/يوم</li>
                        <li><i class="fas fa-check"></i> إشعارات SMS</li>
                        <li class="text-muted"><i class="fas fa-times"></i> التقارير المتقدمة</li>
                    </ul>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 rounded-pill">اشترك الآن</a>
                </div>
            </div>

            <!-- الباقة المتقدمة -->
            <div class="col-md-4">
                <div class="pricing-card featured p-4 text-center position-relative">
                    <div class="badge-popular">الأكثر شعبية</div>
                    <div class="mb-4">
                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                        <h3>الباقة المتقدمة</h3>
                        <p class="text-muted">للعيادات المتوسطة</p>
                    </div>
                    <div class="price mb-4">
                        299<span>ج.م/شهر</span>
                    </div>
                    <ul class="feature-list text-start mb-4">
                        <li><i class="fas fa-check"></i> 10 أطباء</li>
                        <li><i class="fas fa-check"></i> 200 مريض</li>
                        <li><i class="fas fa-check"></i> 50 موعد/يوم</li>
                        <li><i class="fas fa-check"></i> إشعارات SMS و Email</li>
                        <li><i class="fas fa-check"></i> التقارير المتقدمة</li>
                    </ul>
                    <a href="{{ route('login') }}" class="btn btn-gradient w-100">اشترك الآن</a>
                </div>
            </div>

            <!-- الباقة الاحترافية -->
            <div class="col-md-4">
                <div class="pricing-card p-4 text-center position-relative">
                    <div class="mb-4">
                        <i class="fas fa-crown fa-3x text-warning mb-3"></i>
                        <h3>الباقة الاحترافية</h3>
                        <p class="text-muted">للمستشفيات والمراكز الكبيرة</p>
                    </div>
                    <div class="price mb-4">
                        599<span>ج.م/شهر</span>
                    </div>
                    <ul class="feature-list text-start mb-4">
                        <li><i class="fas fa-check"></i> أطباء غير محدود</li>
                        <li><i class="fas fa-check"></i> مرضى غير محدود</li>
                        <li><i class="fas fa-check"></i> مواعيد غير محدودة</li>
                        <li><i class="fas fa-check"></i> جميع الإشعارات</li>
                        <li><i class="fas fa-check"></i> دعم فني 24/7</li>
                    </ul>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 rounded-pill">اشترك الآن</a>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <p class="text-white-50 mb-3">هل لديك حساب بالفعل؟</p>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg rounded-pill">
                <i class="fas fa-sign-in-alt ms-2"></i>
                تسجيل الدخول
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

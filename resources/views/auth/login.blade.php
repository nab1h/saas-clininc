<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.3.0/css/all.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .login-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .login-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .login-body {
            padding: 40px 30px;
        }
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            color: #333;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 12px;
        }
        .social-btn:hover {
            background: #f8f9fa;
            border-color: #667eea;
            transform: translateY(-2px);
        }
        .social-btn.google {
            color: #ea4335;
        }
        .social-btn.facebook {
            color: #1877f2;
        }
        .social-btn.apple {
            color: #000;
        }
        .social-btn i {
            font-size: 20px;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #999;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }
        .divider span {
            padding: 0 15px;
            font-size: 14px;
        }
        .form-control {
            padding: 14px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1><i class="fas fa-hospital-alt"></i> عيادة</h1>
            <p>أهلاً بك مجدداً</p>
        </div>
        <div class="login-body">
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
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Social Login Buttons -->
            <div class="mb-4">
                <a href="{{ url('auth/google') }}" class="social-btn google">
                    <i class="fab fa-google"></i>
                    <span>تسجيل الدخول بـ Google</span>
                </a>
                <a href="{{ url('auth/facebook') }}" class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                    <span>تسجيل الدخول بـ Facebook</span>
                </a>
                <a href="{{ url('auth/apple') }}" class="social-btn apple">
                    <i class="fab fa-apple"></i>
                    <span>تسجيل الدخول بـ Apple</span>
                </a>
            </div>

            <div class="divider">
                <span>أو</span>
            </div>

            <!-- Regular Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" id="email" name="email" required autofocus
                           value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    تسجيل الدخول
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="mb-0">ليس لديك حساب؟
                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #667eea;">
                        إنشاء حساب جديد
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

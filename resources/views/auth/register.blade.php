<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إنشاء حساب - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.3.0/css/all.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .register-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .register-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .register-body {
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
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <h1><i class="fas fa-hospital-alt"></i> عيادة</h1>
            <p>أنشئ حسابك الجديد</p>
        </div>
        <div class="register-body">
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

            <!-- Social Login Buttons -->
            <div class="mb-4">
                <a href="{{ url('auth/google') }}" class="social-btn google">
                    <i class="fab fa-google"></i>
                    <span>التسجيل بـ Google</span>
                </a>
                <a href="{{ url('auth/facebook') }}" class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                    <span>التسجيل بـ Facebook</span>
                </a>
                <a href="{{ url('auth/apple') }}" class="social-btn apple">
                    <i class="fab fa-apple"></i>
                    <span>التسجيل بـ Apple</span>
                </a>
            </div>

            <div class="divider">
                <span>أو</span>
            </div>

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">الاسم الكامل</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                           name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                           id="password-confirm" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-register">
                    إنشاء الحساب
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="mb-0">لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #667eea;">
                        تسجيل الدخول
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

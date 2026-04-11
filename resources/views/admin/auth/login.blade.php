<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - {{ \App\Models\Setting::get('company_name', 'PT. CMIC') }}</title>
    @php
        $favicon   = \App\Models\Setting::get('company_favicon');
        $colorPri  = \App\Models\Setting::get('theme_color_primary',   '#0057A8');
        $colorSec  = \App\Models\Setting::get('theme_color_secondary', '#003A78');
        $colorAcc  = \App\Models\Setting::get('theme_color_accent',    '#F5C518');
    @endphp
    @if($favicon)
    <link rel="icon" href="{{ asset('storage/'.$favicon) }}">
    @else
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary:   {{ $colorPri }};
            --color-secondary: {{ $colorSec }};
            --color-accent:    {{ $colorAcc }};
        }
        * { font-family: 'Poppins', sans-serif; }
        body {
            background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-card { background: #fff; border-radius: 16px; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h4 { color: var(--color-secondary); font-weight: 700; }
        .login-header p { color: #888; font-size: 13px; }
        .icon-circle { background: var(--color-secondary); display:inline-flex; align-items:center; justify-content:center; width:70px; height:70px; border-radius:50%; margin-bottom:15px; }
        .btn-login { background: var(--color-primary); color: #fff; padding: 11px; font-weight: 600; border: none; width: 100%; border-radius: 8px; }
        .btn-login:hover { background: var(--color-secondary); color: #fff; }
        .form-control:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(0,87,168,0.15); }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div class="icon-circle">
            <i class="fas fa-user-shield fa-2x text-white"></i>
        </div>
        <h4>Admin Panel</h4>
        <p>{{ \App\Models\Setting::get('company_name', 'PT. Citra Muda Indo Consultant') }}</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger" style="font-size:13px;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@cmic.co.id" required autofocus>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label" style="font-size:13px;">Ingat saya</label>
            </div>
        </div>
        <button type="submit" class="btn btn-login">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk
        </button>
    </form>
    <div class="text-center mt-4">
        <a href="{{ route('home') }}" style="font-size:13px; color:#888; text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Website
        </a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

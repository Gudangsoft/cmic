<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Sedang Dalam Pemeliharaan' }} — {{ $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant' }}</title>
    @if(!empty($siteSettings['company_favicon']))
        <link rel="icon" href="{{ asset('storage/'.($siteSettings['company_favicon'])) }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap">
    <style>
        :root {
            --cmic-blue:      {{ $siteSettings['theme_color_primary']   ?? '#0057A8' }};
            --cmic-dark-blue: {{ $siteSettings['theme_color_secondary'] ?? '#003A78' }};
            --cmic-yellow:    {{ $siteSettings['theme_color_accent']    ?? '#F5C518' }};
        }
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }

        html, body {
            height: 100%; margin: 0; padding: 0;
            background: linear-gradient(135deg, var(--cmic-dark-blue) 0%, var(--cmic-blue) 60%, #0069c2 100%);
        }

        .maintenance-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            opacity: .07;
            background: #fff;
            pointer-events: none;
        }
        .bg-circle-1 { width: 520px; height: 520px; top: -160px; left: -160px; }
        .bg-circle-2 { width: 380px; height: 380px; bottom: -120px; right: -120px; }
        .bg-circle-3 { width: 200px; height: 200px; bottom: 60px; left: 80px; }

        .maintenance-card {
            background: rgba(255,255,255,.97);
            border-radius: 20px;
            padding: 52px 48px 44px;
            max-width: 560px;
            width: 100%;
            text-align: center;
            box-shadow: 0 24px 80px rgba(0,0,0,.28);
            position: relative;
            z-index: 2;
        }

        .icon-gear {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 90px; height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--cmic-blue), var(--cmic-dark-blue));
            box-shadow: 0 8px 28px rgba(0,57,120,.3);
            margin-bottom: 28px;
            animation: spin-slow 8s linear infinite;
        }
        .icon-gear i { font-size: 38px; color: #fff; }
        @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .maintenance-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--cmic-dark-blue);
            margin-bottom: 12px;
            line-height: 1.25;
        }
        .maintenance-message {
            font-size: 15px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .status-bar {
            background: #f1f5f9;
            border-radius: 50px;
            padding: 10px 22px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: #334155;
            font-weight: 500;
        }
        .status-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--cmic-yellow);
            box-shadow: 0 0 0 3px rgba(245,197,24,.3);
            animation: pulse-dot 1.4s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%       { transform: scale(1.3); opacity: .7; }
        }

        .logo-wrap {
            margin-top: 36px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }
        .logo-wrap img { max-height: 48px; object-fit: contain; }
        .logo-wrap .company-name {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-top: 6px;
        }

        @media (max-width: 576px) {
            .maintenance-card { padding: 36px 22px 32px; }
            .maintenance-title { font-size: 20px; }
        }
    </style>
</head>
<body>
<div class="maintenance-wrapper">
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    <div class="bg-circle bg-circle-3"></div>

    <div class="maintenance-card">
        <div class="icon-gear">
            <i class="fas fa-cog"></i>
        </div>

        <div class="maintenance-title">{{ $title ?? 'Sedang Dalam Pemeliharaan' }}</div>
        <div class="maintenance-message">{{ $message ?? 'Website sedang dalam pemeliharaan. Silakan kunjungi kembali beberapa saat lagi.' }}</div>

        <div class="status-bar">
            <span class="status-dot"></span>
            Tim kami sedang bekerja keras untuk Anda
        </div>

        <div class="logo-wrap">
            @if(!empty($siteSettings['company_logo']))
                <img src="{{ asset('storage/'.$siteSettings['company_logo']) }}" alt="{{ $siteSettings['company_name'] ?? '' }}">
            @endif
            <div class="company-name">{{ $siteSettings['company_name'] ?? 'PT. Citra Muda Indo Consultant' }}</div>
        </div>
    </div>
</div>
</body>
</html>

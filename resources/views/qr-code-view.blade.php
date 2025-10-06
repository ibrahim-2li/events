<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض رمز QR - {{ $event->title }}</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .event-info {
            margin-bottom: 30px;
        }

        .event-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .event-details {
            color: #666;
            font-size: 1.1rem;
        }

        .qr-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            border: 2px dashed #dee2e6;
        }

        .qr-code {
            max-width: 100%;
            height: auto;
        }

        .qr-token {
            background: #e9ecef;
            padding: 10px 20px;
            border-radius: 10px;
            font-family: monospace;
            font-size: 1.1rem;
            margin: 20px 0;
            word-break: break-all;
        }

        .instructions {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: right;
        }

        .instructions h3 {
            margin-top: 0;
            color: #0c5460;
        }

        .instructions ul {
            margin: 10px 0;
            padding-right: 20px;
        }

        .instructions li {
            margin: 5px 0;
            color: #0c5460;
        }

        .actions {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
    </style>
</head>

<body>
    <a href="{{ route('filament.dashboard.auth.login') }}" class="back-btn">← العودة للوحة التحكم</a>

    <div class="container">
        <div class="event-info">
            <h1 class="event-title">{{ $event->title }}</h1>
            <div class="event-details">
                <p><strong>التاريخ:</strong> {{ $event->start_date->format('Y-m-d H:i') }}</p>
                @if ($event->location)
                    <p><strong>المكان:</strong> {{ $event->location }}</p>
                @endif
            </div>
        </div>

        <div class="qr-container">
            <h2 style="margin-top: 0; color: #333;">رمز QR الخاص بك</h2>
            <div class="qr-code">
                {!! $qrCode !!}
            </div>
            <div class="qr-token">
                <strong>الرمز:</strong> {{ $attendance->qr_token }}
            </div>
        </div>

        <div class="instructions">
            <h3>تعليمات الاستخدام:</h3>
            <ul>
                <li>احفظ هذا الرمز على هاتفك المحمول</li>
                <li>اعرض الرمز عند وصولك للحدث</li>
                <li>سيتم مسح الرمز من قبل المنظمين</li>
                <li>يمكن استخدام الرمز مرة واحدة فقط</li>
                <li>تأكد من أن الرمز واضح ومقروء</li>
            </ul>
        </div>

        <div class="actions">
            <a href="{{ route('download-qr', $attendance->id) }}" class="btn">
                📥 تحميل SVG
            </a>
            <a href="{{ route('filament.dashboard.auth.login') }}" class="btn btn-secondary">
                🏠 العودة للوحة التحكم
            </a>
        </div>
    </div>
</body>

</html>

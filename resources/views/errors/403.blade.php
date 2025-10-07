<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>غير مصرح - 403</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .error-container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 500px;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error-message {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4ade80, #22c55e);
            color: white;
            box-shadow: 0 5px 15px rgba(74, 222, 128, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(74, 222, 128, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">🚫</div>
        <h1 class="error-title">غير مصرح</h1>
        <p class="error-message">
            ليس لديك صلاحية للوصول إلى هذه الصفحة.<br>
            يجب أن تكون مدير أو ماسح QR للوصول إلى ماسح الرموز.
        </p>
        <div class="error-actions">
            {{-- <a href="{{ route('filament.dashboard.auth.login') }}" class="btn btn-primary">
                تسجيل الدخول
            </a> --}}
            <a href="{{ route('events.index') }}" class="btn btn-secondary">
                العودة للرئيسية
            </a>
        </div>
    </div>
</body>

</html>

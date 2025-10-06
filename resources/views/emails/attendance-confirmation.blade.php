<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد التسجيل في الحدث</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
        }

        .event-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .event-info h2 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 22px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-label {
            font-weight: bold;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .qr-code {
            margin: 20px 0;
        }

        .qr-code img {
            border: 3px solid #2c3e50;
            border-radius: 8px;
            padding: 10px;
            background-color: white;
        }

        .instructions {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }

        .instructions h3 {
            color: #1976d2;
            margin-top: 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎉 تم تأكيد تسجيلك بنجاح!</h1>
        </div>

        <div class="success-message">
            <strong>مرحباً {{ $attendance->attendee_name }}!</strong><br>
            تم تأكيد تسجيلك في الحدث بنجاح. يرجى الاحتفاظ بهذا البريد الإلكتروني ورمز QR للمراجعة عند الحضور.
        </div>

        <div class="event-info">
            <h2>📅 تفاصيل الحدث</h2>
            <div class="info-row">
                <span class="info-label">اسم الحدث:</span>
                <span class="info-value">{{ $event->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">الوصف:</span>
                <span class="info-value">{{ $event->description }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">تاريخ البداية:</span>
                <span class="info-value">{{ $event->start_date->format('Y-m-d H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">تاريخ النهاية:</span>
                <span class="info-value">{{ $event->end_date->format('Y-m-d H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">الموقع:</span>
                <span class="info-value">{{ $event->location }}</span>
            </div>
        </div>

        <div class="qr-section">
            <h3>📱 رمز QR الخاص بك</h3>
            <p>يرجى عرض هذا الرمز عند وصولك للحدث للتحقق من حضورك:</p>
            <div class="qr-code">
                {!! $qrCode !!}
            </div>
            <p><strong>رقم التسجيل:</strong> {{ $attendance->qr_token }}</p>
        </div>

        <div class="instructions">
            <h3>📋 تعليمات مهمة</h3>
            <ul>
                <li>احتفظ بهذا البريد الإلكتروني ورمز QR</li>
                <li>احضر في الوقت المحدد للحدث</li>
                <li>اعرض رمز QR عند وصولك للتحقق من حضورك</li>
                <li>في حالة فقدان البريد الإلكتروني، يمكنك الاتصال بنا</li>
            </ul>
        </div>

        <div class="footer">
            <p>شكراً لك على التسجيل في حدثنا!</p>
            <p>هذا بريد إلكتروني تلقائي، يرجى عدم الرد عليه.</p>
        </div>
    </div>
</body>

</html>

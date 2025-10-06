<div class="space-y-6">
    <!-- Event Information -->
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            {{ $event->title }}
        </h3>
        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
            <p><strong>التاريخ:</strong> {{ $event->start_date->format('Y-m-d H:i') }}</p>
            @if ($event->location)
                <p><strong>المكان:</strong> {{ $event->location }}</p>
            @endif
            <p><strong>الرمز:</strong> {{ $attendance->qr_token }}</p>
        </div>
    </div>

    <!-- QR Code Display -->
    <div class="text-center">
        <div class="inline-block p-4 bg-white dark:bg-gray-700 rounded-lg shadow-lg">
            {!! $qrCode !!}
        </div>
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            امسح هذا الرمز عند وصولك للحدث
        </p>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
        <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-2">
            تعليمات الاستخدام:
        </h4>
        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
            <li>• احفظ هذا الرمز على هاتفك المحمول</li>
            <li>• اعرض الرمز عند وصولك للحدث</li>
            <li>• سيتم مسح الرمز من قبل المنظمين</li>
            <li>• يمكن استخدام الرمز مرة واحدة فقط</li>
        </ul>
    </div>
</div>

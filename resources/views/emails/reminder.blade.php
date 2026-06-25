<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0f172a; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8fafc; }
        .footer { text-align: center; padding: 20px; color: #94a3b8; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reminder</h2>
        </div>
        <div class="content">
            <h3>{{ $reminder->title }}</h3>
            <p>{{ $reminder->description ?? 'No additional details.' }}</p>
            @if($reminder->remindable)
                <p><strong>Related to:</strong> {{ class_basename($reminder->remindable) }} - 
                <a href="{{ url('/' . strtolower(class_basename($reminder->remindable)) . '/' . $reminder->remindable_id) }}">
                    View {{ class_basename($reminder->remindable) }}
                </a></p>
            @endif
            <p><strong>Reminded at:</strong> {{ $reminder->remind_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        h1 { font-size: 1.25rem; }
        p { margin: 1rem 0; }
    </style>
</head>
<body>
    <h1>Thank you for contacting Atlantic Garden Furniture</h1>
    <p>Hi {{ $inquiry->name }},</p>
    <p>We have received your inquiry. Our team will respond within 24 hours.</p>
    <p>If you have any urgent questions, you can reach us on WhatsApp: {{ config('zuaaz.whatsapp.primary') }}.</p>
    <p>Best regards,<br>{{ config('zuaaz.name', 'Atlantic Garden Furniture') }}</p>
</body>
</html>

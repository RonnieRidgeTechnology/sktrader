<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        h1 { font-size: 1.25rem; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th, td { text-align: left; padding: 8px 0; border-bottom: 1px solid #eee; }
        th { width: 140px; color: #666; font-weight: 600; }
    </style>
</head>
<body>
    <h1>New inquiry received</h1>
    <p>A new inquiry has been submitted from the website.</p>
    <table>
        <tr><th>Name</th><td>{{ $inquiry->name }}</td></tr>
        <tr><th>Company</th><td>{{ $inquiry->company_name ?? '—' }}</td></tr>
        <tr><th>Email</th><td>{{ $inquiry->email }}</td></tr>
        <tr><th>WhatsApp</th><td>{{ $inquiry->whatsapp ?? '—' }}</td></tr>
        <tr><th>Country</th><td>{{ $inquiry->country ?? '—' }}</td></tr>
        <tr><th>Product interest</th><td>{{ $inquiry->product_interest ?? '—' }}</td></tr>
        <tr><th>Quantity / MOQ</th><td>{{ $inquiry->quantity ?? '—' }}</td></tr>
        <tr><th>Message</th><td>{{ $inquiry->message }}</td></tr>
        <tr><th>IP</th><td>{{ $inquiry->ip_address }}</td></tr>
        <tr><th>Date</th><td>{{ $inquiry->created_at->format('Y-m-d H:i') }}</td></tr>
    </table>
    @if($inquiry->attachment)
    <p>An attachment was uploaded. Check public/media/inquiries.</p>
    @endif
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Maintenance – {{ $siteName }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, sans-serif; background: #fafafa; color: #27272a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .card { max-width: 28rem; width: 100%; padding: 2.5rem; text-align: center; background: #fff; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        h1 { font-size: 1.5rem; font-weight: 700; margin: 0 0 .5rem; }
        p { margin: 0; color: #71717a; font-size: .9375rem; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="card">
        <h1>We'll be back shortly</h1>
        <p>{{ $siteName }} is currently under maintenance. Please try again in a few minutes.</p>
    </div>
</body>
</html>

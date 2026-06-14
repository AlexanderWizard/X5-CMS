<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $siteName }}</title>
    <style>
        :root { color-scheme: light dark; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            padding: 1.5rem;
        }
        .box { max-width: 520px; text-align: center; }
        .icon {
            width: 64px; height: 64px; margin: 0 auto 1.25rem;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(249, 115, 22, .15);
            color: #f97316;
            font-size: 32px;
        }
        h1 { font-size: 1.6rem; margin: 0 0 .75rem; color: #fff; }
        p { margin: 0; line-height: 1.6; color: #94a3b8; font-size: 1.05rem; }
        .brand { margin-top: 2rem; font-size: .85rem; color: #475569; letter-spacing: .04em; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon">&#9881;</div>
        <h1>{{ $siteName }}</h1>
        <p>{{ $message }}</p>
        <div class="brand">{{ $siteName }}</div>
    </div>
</body>
</html>

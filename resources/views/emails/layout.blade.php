<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StreetFoodies')</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #0f0f23;
            color: #e5e7eb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: #1a1a2e;
            border-radius: 16px;
            border: 1px solid rgba(107, 114, 128, 0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #f97316, #ef4444);
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: white;
            font-size: 24px;
            font-weight: 700;
        }
        .body {
            padding: 32px 24px;
        }
        .body p {
            margin: 0 0 16px;
            line-height: 1.6;
            font-size: 15px;
            color: #d1d5db;
        }
        .button {
            display: inline-block;
            padding: 12px 32px;
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: white !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            margin: 16px 0;
        }
        .footer {
            text-align: center;
            padding: 24px;
            border-top: 1px solid rgba(107, 114, 128, 0.15);
            font-size: 12px;
            color: #6b7280;
        }
        .footer a {
            color: #f97316;
            text-decoration: none;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
        }
        .badge-approved { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); }
        .badge-rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
        .highlight-box {
            background: rgba(249, 115, 22, 0.08);
            border-left: 3px solid #f97316;
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin: 16px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>

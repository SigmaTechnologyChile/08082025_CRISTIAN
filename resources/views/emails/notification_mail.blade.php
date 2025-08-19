<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 32px 24px 32px;
        }
        .header {
            border-bottom: 1px solid #e0e6ed;
            margin-bottom: 24px;
            padding-bottom: 12px;
        }
        .header h2 {
            color: #2a4365;
            margin: 0;
            font-size: 1.7rem;
        }
        .message {
            color: #333;
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .footer {
            font-size: 0.95rem;
            color: #7b8794;
            border-top: 1px solid #e0e6ed;
            padding-top: 16px;
            margin-top: 24px;
            text-align: right;
        }
        .org {
            color: #3182ce;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $title }}</h2>
        </div>
        <div class="message">
            {!! nl2br(e($body)) !!}
        </div>
        <div class="footer">
            Enviado por <span class="org">{{ $org->name }}</span>
        </div>
    </div>
</body>
</html>

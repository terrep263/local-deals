<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Preview - {{ $template->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .email-header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .email-subject {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .email-body {
            line-height: 1.6;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>{{ $template->name }}</h2>
        </div>
        <div class="email-subject">
            <strong>Subject:</strong> {{ $subject }}
        </div>
        <div class="email-body">
            {!! $body !!}
        </div>
    </div>
</body>
</html>



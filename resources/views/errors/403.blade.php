<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Pages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .error-container {
            max-width: 600px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #dc3545;
        }
        .error-message {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .btn-home {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code" id="error-code">403</div>
        <div class="error-message" id="error-message">Forbidden! You don't have permission to access this page.</div>
        <p class="text-muted">Please contact your administrator if you believe this is an error.</p>
        {{-- <a href="{{ url('/') }}" class="btn btn-primary btn-home">Go to Homepage</a> --}}
    </div>
</body>
</html>

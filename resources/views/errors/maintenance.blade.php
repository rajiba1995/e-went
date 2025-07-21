<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.remixicon.com/releases/v4.2.0/remixicon.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f9fafb;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        .error-container {
            max-width: 600px;
        }
        .error-code {
            font-size: 100px;
            font-weight: bold;
            color: #ffc107;
        }
        .error-icon {
            font-size: 64px;
            color: #ffc107;
            margin-bottom: 10px;
        }
        .error-message {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 15px;
        }
        .text-muted {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="ri-tools-line"></i>
        </div>
        <div class="error-code">503</div>
        <div class="error-message">We're Updating Things Behind the Scenes</div>
        <p class="text-muted">Our team is making improvements to enhance your experience. Please check back in a few minutes.</p>
        <a href="{{ url()->previous() }}" class="btn btn-outline-warning mt-3">Go Back</a>
    </div>
</body>
</html>

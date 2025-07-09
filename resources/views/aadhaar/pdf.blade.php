<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aadhaar Details</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
        }
        .container {
            border: 1px solid #ccc;
            padding: 20px;
            width: 100%;
            position: relative;
        }
        .photo {
            width: 100px;
            height: auto;
            position: absolute;
            right: 20px;
            top: 20px;
            border: 1px solid #999;
        }
        .info {
            padding-right: 120px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Aadhaar KYC Details</h2>

        @if($photo)
            <img src="{{ $photo }}" class="photo">
        @endif

        <div class="info">
            <p><span class="label">Name:</span> {{ $name }}</p>
            <p><span class="label">Date of Birth:</span> {{ $dob }}</p>
            <p><span class="label">Aadhaar Number:</span> {{ $maskedUid }}</p>
            <p><span class="label">Gender:</span> {{ $gender }}</p>
            <p><span class="label">Address:</span><br>
                {{ $address['house'] }}, {{ $address['street'] }}<br>
                {{ $address['vtc'] }}, {{ $address['dist'] }}<br>
                {{ $address['state'] }} - {{ $address['pc'] }}<br>
                {{ $address['country'] }}
            </p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ICICI Payment Response</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f8fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .box {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .box h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            font-weight: 500;
            color: #007bff;
        }
        .alert-custom {
            max-width: 500px;
            margin: 80px auto;
        }
    </style>
</head>
<body>

    @if(!empty($message))
        <div class="alert alert-danger text-center alert-custom">
            {{ $message }}
        </div>
    @elseif(!empty($response))
        <div class="alert alert-success text-center alert-custom">
            {{ $success_message }}
        </div>
        <div class="box">
            <h3>Payment Summary</h3>
            <div class="info-row">
                <div class="label">Amount</div>
                <div class="value">{{ $response['amount'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Response</div>
                <div class="value">{{ $response['respDescription'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Transaction ID</div>
                <div class="value">{{ $response['txnID'] ?? 'N/A' }}</div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center alert-custom">
            No response received.
        </div>
    @endif

</body>

 <script type="text/javascript">
  window.onload = function () {
    if (window.FlutterChannel) {
      FlutterChannel.postMessage("thankyou_redirect");
    } else {
      setTimeout(function () {
        if (window.FlutterChannel) {
          FlutterChannel.postMessage("thankyou_redirect");
        } else {
          console.log("FlutterChannel not available.");
        }
      }, 500);
    }
  };
</script>
</html>

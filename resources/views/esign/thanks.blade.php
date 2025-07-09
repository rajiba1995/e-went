<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 100px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #666;
        }
    </style>

</head>
<body>
    <h1>Please wait, we are processing your request...</h1>
    <p>We are refreshing the page. Please do not close or navigate away.</p>
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

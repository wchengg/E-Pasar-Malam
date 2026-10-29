<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../Css/confirmation.css">
  <title>Order Confirmation</title>
</head>
<body>
  <div class="container">
    <h1>Order Confirmed</h1>
    <p>Thank you for your order! Your order number is <strong>#12345</strong>.</p>
    <p>You can pick up your order at the selected time.</p>
    <button onclick="goToOrderHistory()">View Order History</button>
  </div>

  <script>
    function goToOrderHistory() {
      window.location.href = 'history.html';
    }
  </script>
</body>
</html>

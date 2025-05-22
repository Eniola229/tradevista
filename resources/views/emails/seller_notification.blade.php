<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>New Order Notification</title>
  <style>
      body { font-family: Arial, sans-serif; color: #333; }
      .container { width: 90%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; }
      h1 { background: #4CAF50; color: #fff; padding: 10px; }
      .order-details { margin-top: 20px; }
      table { width: 100%; border-collapse: collapse; }
      th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
      .footer { margin-top: 20px; font-size: 12px; color: #888; }
  </style>
</head>
<body>
<div class="container">
    <h1>New Order Received</h1>
    <p>Hello {{ $seller->name }},</p>
    <p>You have received a new order. Please see the details below:</p>
    <div class="order-details">
        <p><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
        <p><strong>Total Amount:</strong> ₦{{ number_format($order->total, 2) }} (This include shipping fee)</p>
        <h3>Products Ordered:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                </tr>
            </thead> 
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>₦{{ number_format($product->product_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Your Earnings:</strong> ₦{{ number_format($amount, 2) }}</p>
    </div>
    <div class="footer">
        <p>This is an automated message from {{ config('app.name') }}. Please do not reply.</p>
    </div>
</div>
</body>
</html>

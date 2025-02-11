<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>New Order Placed</title>
  <style>
      body { font-family: Arial, sans-serif; color: #333; }
      .container { width: 90%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; }
      h1 { background: #007BFF; color: #fff; padding: 10px; }
      .order-details { margin-top: 20px; }
      table { width: 100%; border-collapse: collapse; }
      th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
      .footer { margin-top: 20px; font-size: 12px; color: #888; }
  </style>
</head>
<body>
<div class="container">
    <h1>New Order Placed</h1>
    <p>Hello Admin,</p>
    <p>A new order has been placed. Please review the details below:</p>
    <div class="order-details">
        <p><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
        <p><strong>Total Amount:</strong> ₦{{ number_format($order->total, 2) }}</p>
        <h3>Products Ordered:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAmount = 0; @endphp
                @foreach($order->orderProducts as $op)
                    @php
                        $subtotal = $op->product_price * $op->product_qty;
                        $totalAmount += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $op->product->product_name ?? 'Unknown Product' }}</td> <!-- Fix: Access product name via relation -->
                        <td>₦{{ number_format($op->product_price, 2) }}</td>
                        <td>{{ $op->product_qty }}</td>
                        <td>₦{{ number_format($subtotal, 2) }}</td> <!-- Fix: Calculate total -->
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Grand Total:</th>
                    <th>₦{{ number_format($totalAmount, 2) }}</th> <!-- Fix: Display grand total -->
                </tr>
            </tfoot>

        </table>
    </div>
    <div class="footer">
        <p>This is an automated message from {{ config('app.name') }}. Please do not reply.</p>
    </div>
</div>
</body>
</html>

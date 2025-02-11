<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>TradeVista - {{ Auth::user()->name }}</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">

  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet">

  <!-- Nepcha Analytics -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
@include('components.sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('components.nav')
      
    @if(session('message'))
        <div class="alert alert-success" style="color: white;">
            {{ session('message') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" style="color: white;">
            {{ session('error') }}
        </div>
    @endif

     <div class="container mt-4">
    <h4>Order Details</h4>
    <div class="card">
        <div class="card-body">
            <h5>Order ID: {{ $order->id }}</h5>
            <p><strong>Total Price:</strong> ₦{{ number_format($order->total_price, 2) }}</p>
            <p><strong>Payment Status:</strong> <span class="{{ $order->payment_status === 'PAID' ? 'text-success' : 'text-danger' }}">{{ $order->payment_status }}</span></p>
            <p><strong>Delivery Status:</strong> {{ $order->delivery_status }}</p>
            <p><strong>Date Added:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
        </div>
    </div>

    <h5 class="mt-4">Ordered Products</h5>
<table class="table table-bordered">
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
        @foreach($order->orderProducts as $orderProduct)
            @php
                $subtotal = $orderProduct->product_price * $orderProduct->product_qty;
                $totalAmount += $subtotal;
            @endphp
            <tr>
                <td>{{ $orderProduct->product->product_name ?? 'Unknown Product' }}</td>
                <td>₦{{ number_format($orderProduct->product_price, 2) }}</td>
                <td>{{ $orderProduct->product_qty }}</td>
                <td>₦{{ number_format($subtotal, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-right">Grand Total:</th>
            <th>₦{{ number_format($totalAmount, 2) }}</th>
        </tr>
    </tfoot>
</table>


    <a href="{{ url('/user/orders') }}" class="btn btn-primary mt-3">Back to Orders</a>
</div>
    </main>

  <!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js') }}?v={{ time() }}"></script>

</body>

</html>
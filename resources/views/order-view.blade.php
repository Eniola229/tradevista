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

<div class="container my-5">
    <div class="card p-4 shadow-sm border-0">
        <h4 class="text-primary fw-bold border-bottom pb-2">Order Details</h4>
        
        <div class="card-body">
            <h5 class="fw-bold">Order ID: <span class="text-muted">#{{ $order->transaction_id }}</span></h5>
            <p class="mb-2"><strong>Total Price:</strong> <span class="text-dark">₦{{ number_format($order->total, 2) }} (Shipping fee included)</span></p>
            <p class="mb-2">
                <strong>Payment Status:</strong> 
                <span class="fw-bold {{ $order->payment_status === 'PAID' ? 'text-success' : 'text-danger' }}">
                    {{ $order->payment_status }}
                </span>
            </p>
            <p class="mb-2"><strong>Delivery Status:</strong> <span class="text-info">{{ $order->delivery_status }}</span></p>
            <p class="mb-2"><strong>Courier Name:</strong> <span class="text-info">{{ $order->courier_name }}</span></p>
            <p class="mb-2"><strong>Shipping Fee:</strong> <span class="text-dark">₦{{ number_format($order->shipping_charges, 2) }} </span></p>
            <p class="mb-2"><strong>Date Added:</strong> <span class="text-secondary">{{ $order->created_at->format('F j, Y g:i A') }}</span></p>
            <p><strong>Order Note:</strong> <span class="text-secondary">{{ $order->order_note ?? 'N/A' }}</span></p>
        </div>

        <h5 class="mt-4 text-primary fw-bold border-bottom pb-2">Ordered Products</h5>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="bg-primary text-white">
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
                            <td class="fw-medium">{{ $orderProduct->product->product_name ?? 'Unknown Product' }}</td>
                            <td>₦{{ number_format($orderProduct->product_price, 2) }}</td>
                            <td class="text-center">{{ $orderProduct->product_qty }}</td>
                            <td class="fw-bold text-success">₦{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-light fw-bold">
                        <th colspan="3" class="text-end">Grand Total:</th>
                        <th class="text-dark">₦{{ number_format($totalAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ url('/user/orders') }}" class="btn btn-primary px-4 py-2 shadow-sm fw-bold">Back to Orders</a>
    </div>
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
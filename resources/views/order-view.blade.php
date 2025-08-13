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

    <!-- Order Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="text-muted mb-1">
                {{ $order->created_at->format('D, M j, Y, g:i A') }} | ID: #{{ $order->transaction_id }}
            </h6>
        </div>
    </div>

    <!-- Order Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Payment & Price -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-credit-card-2-front me-1"></i> Payment</h6>
                    <p class="mb-1"><strong>Total Price:</strong> ₦{{ number_format($order->total, 2) }} <small>(incl. shipping)</small></p>
                    <p class="mb-0">
                        <strong>Status:</strong> 
                        <span class="fw-bold {{ $order->payment_status === 'PAID' ? 'text-success' : 'text-danger' }}">
                            {{ $order->payment_status }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Delivery -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-truck me-1"></i> Delivery</h6>
                    <p class="mb-1"><strong>Status:</strong> <span class="text-info">{{ ucfirst($order->delivery_status) }}</span></p>
                    <p class="mb-1"><strong>Courier Name:</strong> {{ $order->courier_name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Shipping Fee:</strong> ₦{{ number_format($order->shipping_charges, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-info-circle me-1"></i> Order Info</h6>
                    <p class="mb-1"><strong>Date Added:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
                    <p class="mb-0"><strong>Order Note:</strong> {{ $order->order_note ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ordered Products Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <h6 class="fw-bold p-3 border-bottom">Ordered Products</h6>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="bg-light">
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
                                <td class="fw-bold text-success">₦{{ number_format($subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="3" class="text-end">Grand Total:</td>
                            <td>₦{{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Back Button -->
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
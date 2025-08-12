<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

  <title>TradeVista - Admin</title>

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
@include('components.admin-sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('components.admin-nav')
    <!-- End Navbar -->
    
      
    @if(session('message'))
        <div class="alert alert-success" style="color: white;">
            {{ session('message') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" style="color: white;">
            {{ session('error') }}
        </div>
    @endif

<div class="container py-5">
    <div class="row">
        <!-- User Profile Section -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <h4 class="text-primary fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <p class="text-muted">{{ $user->phone_number }}</p>
                   @if($setup)
                        <span class="badge bg-{{ $setup->account_type == 'SELLER' ? 'success' : 'info' }}">
                            {{ strtoupper($setup->account_type) }}
                        </span>
                    @else
                        <span class="badge bg-secondary">Not Set</span>
                    @endif

                    <span class="badge bg-success">
                        ₦{{ number_format($user->balance, 2) }}
                    </span>
                </div>
            </div>

            @if($setup && $setup->account_type === 'SELLER' && $setup->company_image)
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <img src="{{ $setup->company_image }}" class="img-fluid rounded" alt="Company Image">
                    </div>
                </div>
            @endif
        </div>

        <!-- User Setup & Company Details (Only for Sellers) -->
        <div class="col-md-8">
            @if($setup && $setup->account_type === 'SELLER')
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold">Company Details</h4>
                    <p><strong>Company Name:</strong> {{ $setup->company_name }}</p>
                    <p><strong>Description:</strong> {{ $setup->company_description }}</p>
                    <p><strong>State:</strong> {{ $setup->state }}</p>
                    <p><strong>Address:</strong> {{ $setup->address }}</p>
                    <p><strong>Zipcode:</strong> {{ $setup->zipcode }}</p>
                    <p><strong>Contact:</strong> {{ $setup->company_mobile_1 }} | {{ $setup->company_mobile_2 }}</p>
                </div>
            </div>
            @endif

            <!-- Products Section -->
 <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold">Products Uploaded</h4>
                    @if($products->count())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->product_name }}</td>
                                        <td>₦{{ number_format($product->product_price, 2) }}</td>
                                        <td>{{ $product->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $products->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-muted">No products uploaded.</p>
                    @endif
                </div>
            </div>

            <!-- Payments Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold">Payments</h4>
                    @if($payments->count())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-success">
                                    <tr>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Status</th> 
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>₦{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->description }}</td>
                                                                                <td>
                                            @php
                                                switch(strtolower($payment->status)) {
                                                    case 'success':
                                                        $badgeClass = 'bg-success text-white';
                                                        break;
                                                    case 'failed':
                                                        $badgeClass = 'bg-danger text-white';
                                                        break;
                                                    case 'pending':
                                                        $badgeClass = 'bg-warning text-dark';
                                                        break;
                                                    default:
                                                        $badgeClass = 'bg-secondary text-white';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        {{ $payments->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-muted">No payments made.</p>
                    @endif
                </div>
            </div>

            <!-- Orders Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="fw-bold">Orders</h4>
                    @if($orders->count())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>₦{{ number_format($order->total, 2) }}</td>
                                        <td><span class="badge bg-{{ $order->status == 'Completed' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($order->delivery_status) }}
                                        </span></td>
                                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $orders->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-muted">No orders placed.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

  </main>
  <!------Table search--->
  <script type="text/javascript">
function searchTable() {
    // Get the input value and convert it to lowercase
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();

    // Get the table and all rows
    var table = document.getElementById("userTable");
    var rows = table.getElementsByTagName("tr");

    // Loop through all rows, except the header
    for (var i = 1; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName("td");
        var rowMatch = false; // Flag to track if any cell matches the search term

        // Loop through each cell in the row
        for (var j = 0; j < cells.length; j++) {
            var cellText = cells[j].textContent || cells[j].innerText;

            // Check if the cell's text matches the search filter
            if (cellText.toLowerCase().indexOf(filter) > -1) {
                rowMatch = true;
                break; // No need to check further cells in this row if a match is found
            }
        }

        // Show or hide the row based on whether any cell matches
        if (rowMatch) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}
  </script>
  <!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this admin? This action cannot be undone.');
    }
</script>
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
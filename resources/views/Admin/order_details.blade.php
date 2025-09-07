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

<div class="container mt-4">

    <!-- Order Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="text-muted mb-1">
                {{ \Carbon\Carbon::parse($order->created_at)->format('D, M j, Y, g:i A') }} 
                | ID: #{{ $order->transaction_id }}
            </h6>
        </div>
        <div class="d-flex">
            <select  id="delivery_status" class="form-select form-select-sm me-2" style="width:150px">
                <option value="processing" {{ $order->delivery_status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->delivery_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
            </select>
            <button class="btn btn-primary btn-sm" id="update_status">Save</button>
        </div>
    </div>

    <!-- Customer / Pickup / Billing -->
    <div class="row g-3 mb-4">
        <!-- Customer -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-person-fill me-1"></i> Customer</h6>
                    <p class="mb-1"><strong>Full Name:</strong> {{ $order->user->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                    <p class="mb-1"><strong>Mobile Number:</strong> {{ $order->user->phone_number ?? 'N/A' }}</p>
                    <a href="{{ url('admin/view/user', ['id' => $order->user->id]) }}" class="text-primary small">View profile</a>
                </div>
            </div>
        </div>

        <!-- Pickup -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-truck me-1"></i>Shipping Details</h6>
                    <p class="mb-1"><strong>Tracking Number:</strong> N/A</p>
                    <p class="mb-1"><strong>Courier Name:</strong> {{ $order->courier_name }}</p>
                    <p class="mb-1"><strong>Shipping Fee:</strong> ₦{{ number_format($order->shipping_charges ?? 0, 2) }}</p>
                    <p class="mb-0"><strong>Status:</strong> <span class="text-success">Order placed</span></p>
                </div>
            </div>
        </div>

        <!-- Billing -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-geo-alt-fill me-1"></i> Shiiping Address</h6>
                    <p class="mb-1"><strong>City:</strong> {{ $order->city ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Country:</strong> NIGERIA</p>
                    <a href="#" class="text-primary small">Open map</a>
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
                            <th>Product Images</th>
                            <th>Product Name</th>
                            <th>Product Qty</th>
                            <th>Amount</th>
                            <th>Seller Phone Numbers</th>
                            <th>Seller Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderProducts as $orderProduct)
                        <tr>
                            <td>
                                <img src="{{ asset($orderProduct->product->image_url ?? 'placeholder.jpg') }}" 
                                     alt="image" width="50">
                            </td>
                            <td>{{ $orderProduct->product->product_name }}</td>
                            <td>{{ $orderProduct->product_qty }}</td>
                            <td>₦{{ number_format($orderProduct->product_price, 2) }}</td>
                            <td>{{ $orderProduct->product->seller_info->company_mobile_1 ?? 'N/A' }} - {{ $orderProduct->product->seller_info->company_mobile_2 ?? 'N/A' }}</td>
                            <td>{{ $orderProduct->product->seller_info->address ?? 'N/A' }}</td>
                            <td><a href="{{ url('admin/view/user', ['id' => $orderProduct->product->seller_info->user_id]) }}" class="text-primary small">View Seller</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Order Summary -->
            <div class="p-3 border-top">
                <h6 class="fw-bold"><i class="bi bi-receipt me-1"></i> Order Summary</h6>
                <div class="d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <span>₦{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Shipping Fee:</span>
                    <span>₦{{ number_format($order->shipping_charges ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span>₦{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

  </main>
<!-- jQuery & SweetAlert for Updating Status -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    $('#update_status').click(function () {
        let status = $('#delivery_status').val();
        let orderId = "{{ $order->id }}";

        Swal.fire({
            title: "Are you sure?",
            text: "You want to update the delivery status to " + status + "?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, update it!"
        }).then((result) => {
            if (result.isConfirmed) {
                let button = $('#update_status');
                button.html('Updating...').prop('disabled', true);

                $.ajax({
                    url: "/admin/orders/" + orderId + "/update-status",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        delivery_status: status
                    },
                    success: function (response) {
                        Swal.fire("Updated!", response.success, "success");
                        button.html('Update Status').prop('disabled', false);
                    },
                    error: function (xhr) {
                        let backendError = xhr.responseJSON?.error || "Failed to update status.";
                        Swal.fire("Error!", backendError, "error");
                        button.html('Update Status').prop('disabled', false);
                    }
                });
            }
        });
    });
});

</script>
<script>
$(document).ready(function () {
    // Search functionality
    $('#searchOrders').on('keyup', function () {
        var searchText = $(this).val().toLowerCase();
        $('#orderTableBody tr').each(function () {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(searchText));
        });
    });
});

</script>
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
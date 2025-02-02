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
    
       <style>
        .withdraw-container {
            margin: auto;
        }
        .status-badge {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .receipt-links a {
            text-decoration: none;
        }
    </style>
      
    @if(session('message'))
        <div class="alert alert-success" style="color: white;">
            {{ session('message') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" style="color: white;">
            {{ session('error') }}
        </div>
    @endif

         <div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
         <div class="container py-5 d-flex flex-column align-items-center">
            <div class="card shadow-lg p-4 withdraw-container">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0 text-white">Admin - Manage Withdrawals</h4>
                </div>
                <div class="card-body">
                    @if($withdrawals->isEmpty())
                        <p class="text-center text-muted">No withdrawal requests.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Receipt</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $withdrawal)
                                    <tr>
                                        <td class="fw-bold">{{ $withdrawal->user->name }}</td>
                                        <td class="fw-bold">₦{{ $withdrawal->amount }}</td>
                                        <td>
                                            <span class="status-badge 
                                                {{ $withdrawal->status === 'ACCEPTED' ? 'bg-success text-white' : 
                                                   ($withdrawal->status === 'REJECTED' ? 'bg-danger text-white' : 'bg-warning text-dark') }}">
                                                {{ $withdrawal->status }}
                                            </span>
                                        </td>
                                        <td class="receipt-links">
                                            @if($withdrawal->receipt)
                                                <a href="{{ $withdrawal->receipt }}" target="_blank" class="btn btn-success btn-sm">View</a>
                                                <a href="{{ $withdrawal->receipt }}" download class="btn btn-secondary btn-sm">Download</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form class="receiptUploadForm" enctype="multipart/form-data">
                                                <input type="hidden" name="withdrawal_id" value="{{ $withdrawal->id }}">
                                                <input type="file" name="receipt" class="form-control form-control-sm receipt-file" required>
                                                <button type="submit" class="btn btn-primary btn-sm mt-2 upload-btn">
                                                    <span class="default-text">Upload</span>
                                                    <span class="loading-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                        Uploading...
                                                    </span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $withdrawals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

      </div>    
  </main>
  <!-- Add jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('.receiptUploadForm').submit(function(e) {
                e.preventDefault();

                let form = $(this);
                let fileInput = form.find('.receipt-file')[0].files[0];
                let button = form.find('.upload-btn');
                let formData = new FormData();

                formData.append('withdrawal_id', form.find('input[name="withdrawal_id"]').val());
                formData.append('receipt', fileInput);
                formData.append('_token', "{{ csrf_token() }}");

                button.prop('disabled', true);
                button.find('.default-text').addClass('d-none');
                button.find('.loading-text').removeClass('d-none');

                $.ajax({
                    url: "{{ route('admin.uploadReceipt') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                        button.find('.default-text').removeClass('d-none');
                        button.find('.loading-text').addClass('d-none');
                    }
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
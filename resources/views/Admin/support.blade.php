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

         <div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-12 col-7">
                   <div class="d-flex flex-column flex-md-row align-items-center justify-content-between rounded gap-3">
                    <h2 class="h5 text-dark mb-0">Supports</h2>
                  </div>
                
                </div>

              </div>
            </div>
                <!-- Table structure -->
                <div class="card-body px-0 pb-2">
                  <div class="table-responsive">
                    <div class="p-4">
                      <input type="text" id="searchInput" class="form-control" placeholder="Search" onkeyup="searchTable()" style="margin-bottom: 10px;">
                    </div>
                    <table class="table align-items-center mb-0" id="userTable">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Problem Type</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ticket ID</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer Name</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer Email</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Requested</th>
                          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($supports->isNotEmpty())
                        @foreach($supports as $support)
                            <tr>
                                <td>
                                    <h6 class="mb-0 text-sm">{{ $support->problem_type }}</h6>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm">{{ $support->ticket_id }}</h6>
                                </td>
                                <td>
                                   <h6 class="mb-0 text-sm">{{ $support->user->name }}</h6>
                                </td>
                                <td>
                                   <h6 class="mb-0 text-sm">{{ $support->user->email }}</h6>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm {{ $support->status === 'ISSUE FIXED' ? 'text-success' : 'text-danger' }}">{{ $support->status }}</h6>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $support->created_at->format('F j, Y g:i A') }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                  <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                          <!-- Button to trigger the modal -->
                                          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#viewModal{{ $support->id }}">
                                            View
                                          </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal for each support ticket -->
                            <div class="modal fade" id="viewModal{{ $support->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $support->id }}" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel{{ $support->id }}">Ticket Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <!-- Display support ticket details -->
                                    <p><strong>Ticket ID:</strong> {{ $support->ticket_id }}</p>
                                    <p><strong>Customer Name:</strong> {{ $support->user->name }}</p>
                                    <p><strong>Customer Email:</strong> {{ $support->user->email }}</p>
                                    <p><strong>Problem Type:</strong> {{ $support->problem_type }}</p>
                                    <p><strong>Status:</strong> {{ $support->status }}</p>
                                    <p><strong>Date Requested:</strong> {{ $support->created_at->format('F j, Y g:i A') }}</p>
                                    <hr>
                                    <hr style="border: 0; height: 2px; background: linear-gradient(to right, #ff7e5f, #feb47b); margin: 20px 0;">
                                    <p><strong>Question:</strong> {{ $support->message }}</p>
                                    @if(empty($support->answer))
                                        <form id="answerForm" action="{{ url('admin/support/answer', ['id' => $support->id]) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="answer" class="form-label">Your Answer</label>
                                                <textarea class="form-control" id="answer" name="answer" rows="4" required></textarea>
                                            </div>
                                                   <button id="submitBtn" type="submit" class="btn btn-primary">Submit Answer</button>
                                        </form>
                                    @else
                                        <p><strong>Answer:</strong> {{ $support->answer }}</p>
                                        <p><strong>Attended to by:</strong> {{ $support->attendant->name }}</p>
                                    @endif

                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">
                                    <p>No support tickets available.</p>
                                </td>
                            </tr>
                        @endif
                      </tbody>
                    </table>

                    <!-- Pagination outside the loop -->
                    <div class="d-flex justify-content-center">
                        {{ $supports->links('pagination::bootstrap-4') }}
                    </div>
                  </div>
                </div>

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
    $('#answerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        var formData = $(this).serialize(); // Serialize form data
        var submitBtn = $('#submitBtn'); // Get the submit button

        // Disable the button and show loading text
        submitBtn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message using SweetAlert
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        // After closing SweetAlert, reload the page
                        if (result.isConfirmed) {
                            location.reload(); // Reload the page
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Enable the button again if there was an error
                    submitBtn.prop('disabled', false).text('Submit Answer');
                });
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
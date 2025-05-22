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
                    <h2 class="h5 text-dark mb-0">Customers</h2>
                  </div>
                
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
        <!-- Search Input -->
        <div class="p-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Search" onkeyup="searchTable()" style="margin-bottom: 10px;">
        </div>
        <!-- Table -->
        <table class="table align-items-center mb-0" id="userTable">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Account Balance</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
            </tr>
          </thead>
          <tbody>
            @if($users->isNotEmpty())
              @foreach($users as $user)
                <tr>
                  <td>
                    <img src="{{ $user->avatar }}" width="70" alt="{{ $user->name }}">
                  </td>
                  <td>
                    <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                  </td>
                  <td>
                    <h6 class="mb-0 text-sm">₦ {{ number_format($user->balance, 2) }}</h6>
                  </td>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $user->created_at->format('F j, Y g:i A') }}</h6>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <a href="{{ url('admin/view/user', ['id' => $user->id]) }}">
                          <button class="btn btn-success">View</button>
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="5" class="text-center">
                  <p>No user available.</p>
                </td>
              </tr>
            @endif
          </tbody>
        </table>
          <div class="d-flex justify-content-center mt-4">
              {{ $users->links() }}
          </div>
              </div>
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
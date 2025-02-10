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
                    <h2 class="h5 text-dark mb-0">State Prices</h2>
                  </div>
                
                </div>

              </div>
            </div>

            <div class="container mt-4">
              <!-- Button to trigger the create modal -->
              <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                Add New State Price
              </button>

              <!-- Table structure -->
              <div class="card-body px-0 pb-2">
                <div class="table-responsive">
                  <div class="p-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search" onkeyup="searchTable()" style="margin-bottom: 10px;">
                  </div>
                  <table class="table align-items-center mb-0" id="statePriceTable">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Origin</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Destination</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($statePrices as $statePrice)
                      <tr id="row-{{ $statePrice->id }}">
                        <td>{{ $statePrice->origin }}</td>
                        <td>{{ $statePrice->destination }}</td>
                        <td>{{ number_format($statePrice->price, 0) }}</td>
                        <td>{{ $statePrice->created_at->format('F j, Y g:i A') }}</td>
                        <td class="text-center">
                          <button class="btn btn-sm btn-info edit-btn" 
                                  data-id="{{ $statePrice->id }}"
                                  data-origin="{{ $statePrice->origin }}"
                                  data-destination="{{ $statePrice->destination }}"
                                  data-price="{{ $statePrice->price }}">
                            Edit
                          </button>
                          <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $statePrice->id }}">
                            Delete
                          </button>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Create Modal -->
            <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form id="createForm">
                    @csrf
                    <div class="modal-header">
                      <h5 class="modal-title" id="createModalLabel">Add New State Price</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="origin" class="form-label">Origin</label>
                        <input type="text" class="form-control" id="origin" name="origin" required>
                      </div>
                      <div class="mb-3">
                        <label for="destination" class="form-label">Destination</label>
                        <input type="text" class="form-control" id="destination" name="destination" required>
                      </div>
                      <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Save State Price</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-header">
                      <h5 class="modal-title" id="editModalLabel">Edit State Price</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="edit_origin" class="form-label">Origin</label>
                        <input type="text" class="form-control" id="edit_origin" name="origin" required>
                      </div>
                      <div class="mb-3">
                        <label for="edit_destination" class="form-label">Destination</label>
                        <input type="text" class="form-control" id="edit_destination" name="destination" required>
                      </div>
                      <div class="mb-3">
                        <label for="edit_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_price" name="price" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Update State Price</button>
                    </div>
                  </form>
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
  // Simple search filter for the table
  function searchTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("statePriceTable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
      var rowVisible = false;
      td = tr[i].getElementsByTagName("td");
      for (j = 0; j < td.length; j++) {
        if (td[j]) {
          txtValue = td[j].textContent || td[j].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            rowVisible = true;
            break;
          }
        }
      }
      tr[i].style.display = rowVisible ? "" : "none";
    }
  }

  // Helper function to extract error messages from the backend response
  function getErrorMessages(xhr) {
    let errorText = '';
    if(xhr.responseJSON) {
      if(xhr.responseJSON.errors) {
        $.each(xhr.responseJSON.errors, function(key, messages) {
          errorText += messages.join(' ') + "\n";
        });
      } else if(xhr.responseJSON.message) {
        errorText = xhr.responseJSON.message;
      } else {
        errorText = "An unknown error occurred.";
      }
    } else {
      errorText = "An error occurred. Please try again.";
    }
    return errorText;
  }

  // Handle Create Form Submission using AJAX and SweetAlert2
  $('#createForm').submit(function(e) {
    e.preventDefault();
    let form = $(this);
    let submitButton = form.find('button[type="submit"]');
    // Disable the submit button and change text to indicate submitting
    submitButton.prop('disabled', true).text('Submitting...');
    
    $.ajax({
      url: "{{ route('state_prices.store') }}",
      method: "POST",
      data: form.serialize(),
      success: function(response) {
        // Re-enable the button and revert its text
        submitButton.prop('disabled', false).text('Save State Price');
        if(response.success){
          Swal.fire({
            title: 'Success!',
            text: response.message,
            icon: 'success'
          }).then(() => {
            location.reload();
          });
        }
      },
      error: function(xhr) {
        // Re-enable the button and revert its text in case of error
        submitButton.prop('disabled', false).text('Save State Price');
        let errorText = getErrorMessages(xhr);
        Swal.fire('Error!', errorText, 'error');
      }
    });
  });

  // Populate the Edit Modal when an Edit button is clicked
  $('.edit-btn').click(function(){
    let id = $(this).data('id');
    let origin = $(this).data('origin');
    let destination = $(this).data('destination');
    let price = $(this).data('price');
    
    $('#edit_id').val(id);
    $('#edit_origin').val(origin);
    $('#edit_destination').val(destination);
    $('#edit_price').val(price);
    
    $('#editModal').modal('show');
  });

  // Handle Edit Form Submission using AJAX and SweetAlert2
  $('#editForm').submit(function(e){
    e.preventDefault();
    let form = $(this);
    let id = $('#edit_id').val();
    let submitButton = form.find('button[type="submit"]');
    // Disable the submit button and change its text to indicate submitting
    submitButton.prop('disabled', true).text('Submitting...');
    
    $.ajax({
      url: "/admin/state_prices/" + id,
      method: "POST",
      data: form.serialize() + "&_method=PUT",
      success: function(response) {
        // Re-enable the button and revert its text
        submitButton.prop('disabled', false).text('Update State Price');
        if(response.success){
          Swal.fire({
            title: 'Success!',
            text: response.message,
            icon: 'success'
          }).then(() => {
            location.reload();
          });
        }
      },
      error: function(xhr){
        // Re-enable the button and revert its text in case of error
        submitButton.prop('disabled', false).text('Update State Price');
        let errorText = getErrorMessages(xhr);
        Swal.fire('Error!', errorText, 'error');
      }
    });
  });

  // Handle Delete Action with confirmation using SweetAlert2
  $('.delete-btn').click(function(){
    var id = $(this).data('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "This record will be permanently deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "/admin/state_prices/" + id,
          type: "POST",
          data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE'
          },
          success: function(response) {
            if(response.success) {
              Swal.fire('Deleted!', response.message, 'success');
              $('#row-' + id).remove();
            } else {
              Swal.fire('Error!', response.message, 'error');
            }
          },
          error: function(xhr) {
            let errorText = getErrorMessages(xhr);
            Swal.fire('Error!', errorText, 'error');
          }
        });
      }
    });
  });
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
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
    <h4 class="mb-4">Your Ordered Products Pending Review</h4>

    @if(count($pendingOrders) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order Date</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingOrders as $order)
                        @foreach($order->pendingProducts as $product)
                            <tr>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>₦{{ number_format($product->product_price, 2) }}</td>
                                <td>
                                    <button class="btn btn-primary drop-review-btn"
                                            data-order-id="{{ $order->id }}"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->product_name }}">
                                        Drop a Review
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">You have no pending reviews.</p>
    @endif
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="reviewForm">
          @csrf
          <div class="modal-header">
              <h5 class="modal-title" id="reviewModalLabel">Drop Your Review</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <!-- Hidden fields to store order and product IDs -->
              <input type="hidden" name="order_id" id="modal_order_id">
              <input type="hidden" name="product_id" id="modal_product_id">

              <div class="mb-3">
                  <label for="modal_product_name" class="form-label">Product</label>
                  <input type="text" class="form-control" id="modal_product_name" readonly>
              </div>
              <div class="mb-3">
                  <label for="rating" class="form-label">Rating (1-5)</label>
                  <select name="rating" id="rating" class="form-select" required>
                      <option value="5">★★★★★ (5)</option>
                      <option value="4">★★★★☆ (4)</option>
                      <option value="3">★★★☆☆ (3)</option>
                      <option value="2">★★☆☆☆ (2)</option>
                      <option value="1">★☆☆☆☆ (1)</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label for="review" class="form-label">Your Review</label>
                  <textarea name="review" id="review" class="form-control" rows="3" required></textarea>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" id="submitReview" class="btn btn-primary">Submit Review</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
      </form>
    </div>
  </div>
</div>
    </main>

  <!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<!-- jQuery and SweetAlert (Include these if not already loaded in your layout) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap JS bundle (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    // Initialize Bootstrap modal
    var reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'), {});

    // When "Drop a Review" is clicked, populate and show the modal.
    $('.drop-review-btn').click(function(){
        var orderId     = $(this).data('order-id');
        var productId   = $(this).data('product-id');
        var productName = $(this).data('product-name');

        $('#modal_order_id').val(orderId);
        $('#modal_product_id').val(productId);
        $('#modal_product_name').val(productName);

        // Reset the form fields.
        $('#rating').val('5');
        $('#review').val('');

        reviewModal.show();
    });

    // Submit the review form using AJAX.
    $('#reviewForm').submit(function(e){
        e.preventDefault();

        var form = $(this);
        var submitButton = $('#submitReview');
        var originalButtonText = submitButton.text();

        submitButton.prop('disabled', true).text('Processing...');

        $.ajax({
            url: "{{ route('reviews.store') }}",
            type: "POST",
            data: form.serialize(),
            success: function(response){
                reviewModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                }).then(function(){
                    location.reload();
                });
            },
            error: function(xhr){
                // Attempt to extract the actual error message from the backend response.
                var errorMessage = 'An error occurred while submitting your review. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                });
                submitButton.prop('disabled', false).text(originalButtonText);
            }
        });
    });
});
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
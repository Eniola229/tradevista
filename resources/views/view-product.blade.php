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
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">

  <!-- //Sweet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>



<body class="g-sidenav-show  bg-gray-100">
@include('components.sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

   <section class="product-details spad">
    @if(session('message'))
        <div class="alert alert-success" style="color: white;">
            {{ session('message') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" style="color: white;">
            {{ session('error') }}
        </div>
    @endif

    <style type="text/css">
      .collapse {
            display: none;
            transition: all 0.3s ease;
        }

        .collapse.show {
            display: block;
        }

    </style>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__left product__thumb nice-scroll">
                            <a class="pt active" href="#product-1">
                                <img src="{{ $product->image_url }}" alt="">
                            </a>
                               @if($images && $images->isNotEmpty())
                                  @foreach($images as $key => $image)
                                      <a class="pt" href="#product-{{ $key + 2 }}">
                                          <img src="{{ $image->image_url }}" alt="Product Image {{ $key + 2 }}">
                                      </a>
                                  @endforeach
                              @endif
                        </div>
                        <div class="product__details__slider__content">
                            <div class="product__details__pic__slider owl-carousel">
                                <img data-hash="product-1" class="product__big__img" src="{{ $product->image_url }}" alt="">
                                 @if($images && $images->isNotEmpty())
                                    @foreach($images as $key => $image)
                                        <img data-hash="product-{{ $key + 2 }}" class="product__big__img" src="{{ $image->image_url }}" alt="Product Image {{ $key + 2 }}">
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product__details__text">
                        <h3>{{ $product->product_name }} <span>Brand: {{ $setup->company_name }}</span></h3>
                        <div class="rating">
                            @if($reviews->isNotEmpty())
                                @php
                                    $averageRating = round($reviews->avg('rating')); // Calculate the average rating
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $averageRating)
                                        <i class="fa fa-star"></i> <!-- Filled star -->
                                    @else
                                        <i class="fa fa-star-o"></i> <!-- Empty star -->
                                    @endif
                                @endfor
                            @else
                                <!-- Display empty stars if no reviews -->
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            @endif
                        </div>
                            <span>( {{ $reviewCount }} reviews )</span>
                        </div>
                        <div class="product__details__price">
                               ₦@if($product->product_discount === null || $product->product_discount == 0)
                                    {{ number_format($product->product_price, 2) }}
                                @else
                                    {{ number_format($product->product_discount, 2) }}
                                @endif
                                @if($product->product_discount > 0)
                                    <span>₦ {{ number_format($product->product_price, 2) }}</span>
                                @endif
                        </div>

                        <!-- <p>{{ $product->product_description }}</p> -->
                        <div class="product__details__button">

                            <a href="{{ url('add-edit-product', ['id' => $product->id]) }}" class="cart-btn"><span class="icon_bag_alt"></span>Edit</a>
                            <a href="#" class="cart-btn delete-btn" data-id="{{ $product->id }}">
                                <span class="icon_bag_alt"></span>Delete
                            </a> 

                            <!-- Hidden Form for Submission -->
                            <form id="delete-product-form-{{ $product->id }}" action="{{ route('delete-product', $product->id) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <!-- Trigger Button -->
                            <a href="#" class="cart-btn" onclick="showPopup()">
                                <span class="icon_bag_alt"></span>
                                {{ $attribute ? 'Edit Attribute' : 'Add Attribute' }}
                            </a>

                            <!-- Button to Trigger Dropdown -->
                            <button type="button" class="cart-btn" id="toggleAddImages">
                                {{ $image ? 'Edit Images' : 'Add Images' }}
                            </button>

                            <!-- Popup Modal -->
                            <div id="attributePopup" class="modal" style="display: none;">
                                <div class="modal-content">
                                    <span class="close-btn" onclick="closePopup()">&times;</span>
                                    <h3>{{ $attribute ? 'Edit Attribute' : 'Add Attribute' }}</h3>
                                    <form action="{{ route('add-attribute', $product->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="form-group">
                                            <label for="sku">SKU:</label>
                                            <input type="text" name="sku[]" id="sku" value="{{ old('sku', $attribute->sku ?? '') }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="size">Size:</label>
                                            <input type="text" name="size[]" value="{{ old('size', $attribute->size ?? '') }}" id="size" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="price">Price:</label>
                                            <input type="number" name="price" id="price" class="form-control" value="{{ $product->product_price }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="stock">Stock:</label>
                                            <input type="number" name="stock" id="stock" value="{{ old('stock', $attribute->stock ?? '') }}" class="form-control" value="{{ $product->stock }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select name="status[]" id="status" class="form-control">
                                                <option value="ACTIVE">Active</option>
                                                <option value="INACTIVE">Inactive</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-success mt-3">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                            <!-- Collapsible Form -->
                            <div id="addImagesDropdown" class="collapse mt-3">
                                <form action="{{ route('add-images', $product->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card card-body">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Upload Images</label>
                                            <input type="file" name="images[]" id="images" class="form-control" multiple required>
                                            <small class="text-muted">You can upload multiple images at once.</small>
                                        </div>
                                        <button type="submit" class="btn btn-success">Upload</button>
                                    </div>
                                </form>
                            </div>

                        <div class="product__details__widget">
                            <ul>
                                <li>
                                    <span>Availability:</span>
                                    <div class="stock__checkbox">
                                        <label for="stockin">
                                          @if($product->stock == 0)
                                              <p>Out of stock</p>
                                          @else
                                              <p>In stock: {{ $product->stock }}</p>
                                          @endif
                                            <input type="checkbox" id="stockin">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Category:</span>
                                    <div class="color__checkbox">
                                       <label for="xs-btn" class="active">
                                            <input type="radio" id="xs-btn">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Product size:</span>
                                    <div class="size__btn">
                                        <label for="xs-btn" class="active">
                                            <input type="radio" id="xs-btn">
                                            @if($attribute)
                                            {{ $attribute->size }}
                                            @else
                                            <p style="color: red;">NOT AVALIABLE</p>
                                            @endif
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Shipping fee:</span>
                                    <p>₦ {{ $product->shipping_fee }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Meta Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Reviews ( {{ $reviewCount }} )</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <h6>Description</h6>
                                <p>{!! strip_tags($product->product_description, '<p><a><strong><em><ul><li><br>') !!}</p>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <h6>Meta Description</h6>
                                <p>{{ $product->meta_description }}
                                   .</p>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <h6>Reviews ( {{ $reviewCount }} )</h6>
                                @foreach($reviews as $review)
                                <p>
                                  {{ $review->review }}
                                 </p>
                                  
                                  
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      
        </div>
    </section>

  </main>

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach((button) => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default anchor behavior

            const productId = button.getAttribute('data-id');
            const form = document.getElementById(`delete-product-form-${productId}`);

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will delete the product!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });
    });
});

</script>
<!-- JavaScript -->
<script>
    document.getElementById('toggleAddImages').addEventListener('click', function () {
        const dropdown = document.getElementById('addImagesDropdown');
        dropdown.classList.toggle('collapse');
    });
</script>

<script>
function showPopup() {
    document.getElementById('attributePopup').style.display = 'block';
}

function closePopup() {
    document.getElementById('attributePopup').style.display = 'none';
}
</script>

<!-- Modal Styling -->
<style>
.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 20px;
    z-index: 1000;
    width: 300px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}
.modal-content {
    position: relative;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
}
form label {
    display: block;
    margin-top: 10px;
}
form input,
form select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    box-sizing: border-box;
}
</style>
<!--   Core JS Files   -->
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/mixitup.min.js') }}"></script>
<script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

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
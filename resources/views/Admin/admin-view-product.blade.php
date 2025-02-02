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
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">

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
                            @foreach($reviews as $review)
                                <div class="review">
                                    <!-- <p>{{ $review->user->name ?? 'Anonymous' }}</p> -->
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="fa fa-star"></i> <!-- Filled star -->
                                            @else
                                                <i class="fa fa-star-o"></i> <!-- Empty star -->
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No reviews yet.</p>
                        @endif
                            <span>( {{ $reviewCount }} reviews )</span>
                        </div>
                        <div class="product__details__price">
                            ₦ {{ number_format($product->product_discount, 2) }} 
                            <span>₦ {{ number_format($product->product_price, 2) }}</span>
                        </div>

                        <!-- <p>{{ $product->product_description }}</p> -->
                        <div class="product__details__button">
                            <!-- Button to Trigger Dropdown -->
                            <button type="button" class="cart-btn" id="toggleProductStatus" onclick="changeProductStatus('{{ $product->id }}', '{{ $product->status }}')">
                                {{ $product->status == 'ACTIVE' ? 'Deactivate Product' : 'Activate Product' }}
                            </button>

                         

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
                                <p>{{ $product->product_description }}</p>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function changeProductStatus(productId, currentStatus) {
        const newStatus = currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
        
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to change the product status to ${newStatus}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to a route with GET method
                window.location.href = `/admin/change-product-status?product_id=${productId}&new_status=${newStatus}`;
            }
        });
    }
</script>
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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>TradeVista - {{ Auth::user()->name }}</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">


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

         <div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-12 col-7">
                   <div class="d-flex flex-column flex-md-row align-items-center justify-content-between rounded gap-3">
                    <h2 class="h5 text-dark mb-0">Sold Products ({{$productCount}})</h2>
                  </div>
                
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Null</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
               <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Date Sold</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products->isNotEmpty())
                        @foreach($products as $orderProduct)
                            @php $product = $orderProduct->product; @endphp
                            <tr>
                                <td>
                                    <img src="{{ $product->image_url }}" width="70" alt="{{ $product->product_name }}">
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm">{{ $product->product_name }}</h6>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm">₦ {{ number_format($product->product_price, 2) }}</h6>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm {{ $product->status === 'ACTIVE' ? 'text-success' : 'text-danger' }}">{{ $product->status }}</h6>
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm">{{ $orderProduct->created_at->format('F j, Y g:i A') }}</h6>
                                </td>
                                <td>
                                    <a href="{{ url('user/view/product', ['id' => $product->id]) }}">
                                        <button class="btn btn-success">View</button>
                                    </a>
                                    <a href="{{ url('add-edit-product', ['id' => $product->id]) }}">
                                        <button class="btn btn-primary">Edit</button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">
                                <p>No products have been sold yet.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
              </div>
            </div>
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
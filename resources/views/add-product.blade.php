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
    
<div class="container mt-5">
    <h2 class="mb-4">{{ $title }}</h2>

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ isset($productdata) ? route('products.edit', $productdata->id) : route('products.add') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        <div class="card shadow-sm p-4">
            <div class="row">
                <!-- Product Name -->
                <div class="col-md-6 mb-3">
                    <label for="product_name"  maxlength="255" class="form-label">Product Name</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name', $productdata->product_name ?? '') }}" required>
                </div>

                <!-- Product Price -->
                <div class="col-md-6 mb-3">
                    <label for="product_price" class="form-label">Product Price <span style="color: red;">(Kindly Note that on every product, we take 15% of the price as our charges)</span></label>
                    <input type="number" id="product_price" name="product_price" class="form-control" value="{{ old('product_price', $productdata->product_price ?? '') }}" required>
                </div>

                  <!-- Shipping Price -->
                <div class="col-md-6 mb-3">
                    <label for="shipping_fee" class="form-label">Shipping Fee <span style="color: red;">(Kindly Note that this will be use as a defualt shipping fee, for locations close to your company address)</span></label>
                    <input type="number" id="shipping_fee" name="shipping_fee" class="form-control" value="{{ old('shipping_fee', $productdata->shipping_fee ?? '') }}" required>
                </div>


                <!-- Stock -->
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', $productdata->stock ?? '') }}" required>
                </div>

                <!-- Product Weight -->
                <div class="col-md-6 mb-3">
                    <label for="product_weight" class="form-label">Product Weight</label>
                    <input type="text" id="product_weight" name="product_weight" class="form-control" value="{{ old('product_weight', $productdata->product_weight ?? '') }}" required>
                </div>

                <!-- Product Discount -->
                <div class="col-md-6 mb-3">
                    <label for="product_discount" class="form-label">Product Discount <span style="color: red;">(Kindly Note that this will be use as the new price for the product)</span></label>
                    <input type="text" id="product_discount" name="product_discount" class="form-control" value="{{ old('product_discount', $productdata->product_discount ?? '') }}" required>
                </div>

                <!-- Product Description -->
                <div class="col-12 mb-3">
                    <label for="product_description" class="form-label">Product Description</label>
                    <textarea id="product_description" name="product_description" class="form-control" rows="4">{{ old('product_description', $productdata->product_description ?? '') }}</textarea>
                </div>

                <!-- Category -->
               
                    <div class="col-md-6 mb-3">
                        <label for="meta_title" class="form-label">Product Category</label>
                        <select name="category_id" id="categorySelect" class="form-select" required>
                            <option disabled selected value="">Select</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

            <!----Status---->
              <div class="col-md-6 mb-3">
                  <label for="meta_title" class="form-label">Status</label>
                  <select name="status" class="form-select" required>
                          <option value="ACTIVE">ACTIVE</option>
                          <option value="INACTIVE">INACTIVE</option>
                  </select>
              </div>


                <!-- Meta Title -->
                <div class="col-md-6 mb-3">
                    <label for="meta_title" class="form-label">Meta Title (Optional)</label>
                    <input type="text" id="meta_title" name="meta_title" class="form-control"  maxlength="255" value="{{ old('meta_title', $productdata->meta_title ?? '') }}">
                </div>

                <!-- Meta Keywords -->
                <div class="col-md-6 mb-3">
                    <label for="meta_keywords" class="form-label">Meta Keywords (Optional)</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" maxlength="255" class="form-control" value="{{ old('meta_keywords', $productdata->meta_keywords ?? '') }}">
                </div>

                <!-- Meta Description -->
                <div class="col-12 mb-3">
                    <label for="meta_description" class="form-label">Meta Description (Optional)</label>
                    <input type="text" id="meta_description" name="meta_description" maxlength="255" class="form-control" value="{{ old('meta_description', $productdata->meta_description ?? '') }}">
                </div>

                <!-- Product Image -->
                <div class="col-6 mb-3">
                    <label for="main_image" class="form-label">Main Image</label>
                    <input type="file" id="main_image" name="main_image" style="border: 1px solid black" class="form-control-file" required>
                    @if(isset($productdata) && $productdata->image_url)
                        <img src="{{ $productdata->image_url }}" alt="Product Image" class="mt-3" width="150">
                    @endif
                </div>

                <!-- Product Video -->
                <div class="col-6 mb-3">
                    <label for="product_video" class="form-label">Product Video (Max 1 minute) (Optional)</label>
                   <input type="file" id="product_video" name="product_video" class="form-control-file" 
                        accept="video/*" style="border: 1px solid black;">

                    @if(isset($productdata) && $productdata->video_url)
                        <video width="300" controls>
                            <source src="{{ $productdata->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Save Product</button>
            </div>
        </div>
    </form>
</div>



    </main>

  <!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    document.getElementById('productForm').addEventListener('submit', function(event) {
        var categorySelect = document.getElementById('categorySelect');

        if (categorySelect.value === "") {
            alert("Please select a product category before proceeding.");
            event.preventDefault(); // Prevent form submission
        }
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
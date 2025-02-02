<?php
use App\Models\Product;
?>
@include('components.header')
<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
     @include('components.mobile-nav')
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('components.nav-link')
    <!-- Header Section End -->


    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>About</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Blog Section Begin -->
    <section class="blog spad">
      <div class="container">
   <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="mb-4 fw-bold text-primary">About Us</h2>
            <p class="lead text-muted">Welcome to <strong>YourStore</strong>, your premier destination for quality shopping. Our mission is to provide an exceptional shopping experience by offering top-notch products with a commitment to excellence.</p>
            <p class="text-muted">Established in [Year], <strong>YourStore</strong> has grown into a trusted brand known for reliability and superior service. We take pride in curating an extensive range of products that cater to diverse needs while ensuring affordability and customer satisfaction.</p>
            <p class="text-muted">With a global reach and a customer-centric approach, we continuously strive to enhance our offerings and bring you the latest trends. Your satisfaction is our priority, and we are always here to assist you with any inquiries.</p>
            <p class="fw-bold text-secondary">Thank you for choosing YourStore. Happy Shopping!</p>
        </div>
    </div>
</div>

     </div>
    </section>
<!-- Contact Section End -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@include('components.footer')
</html>

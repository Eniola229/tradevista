@include('components.header')
<body>
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
                        <span>Page Not Found</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="contact__content">
                        <div class="contact__form">
                      <h1>Oops! Page Not Found</h1>
                    <p>It looks like this page doesn’t exist anymore.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->



@include('components.footer')
</html>


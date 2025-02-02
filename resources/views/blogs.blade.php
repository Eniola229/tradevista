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
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Blog Section Begin -->
    <section class="blog spad">
      <div class="container">
    <div class="row">
        <!-- Column 1 -->
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="blog__item">
                <div class="blog__item__pic large__item set-bg" data-setbg="https://img.freepik.com/free-photo/computer-mouse-paper-bag-blue-background-top-view_169016-43525.jpg?t=st=1738420002~exp=1738423602~hmac=1c7f831685202b155239380db700a667279222346d04398b776e9c541277a4e5&w=360"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Top 10 E-Commerce Trends to Watch in 2025</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 15, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" data-setbg="https://img.freepik.com/free-photo/vertical-banners-sales-promo_23-2150653397.jpg?t=st=1738420094~exp=1738423694~hmac=f5c12737b48d4669c29727f7196aa2a471a05df3d655dcaa7b63d55288b995cd&w=360"></div>
                <div class="blog__item__text">
                    <h6><a href="#">How to Create a Winning Discount Strategy for Your Store</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 10, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" data-setbg="https://img.freepik.com/free-vector/business-leading-growth-arrows-blue-concept-background_1017-20064.jpg?t=st=1738420140~exp=1738423740~hmac=307adf69db5cdb041f85f800c91e3ac49b0e7511970cc0a75b42bc8e041d7932&w=740" style="background-image: url('https://via.placeholder.com/350x200?text=Customer+Reviews');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">5 Ways to Leverage Customer Reviews to Boost Sales</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 8, 2025</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Column 2 -->
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="blog__item">
                <div class="blog__item__pic set-bg" data-setbg="https://img.freepik.com/free-photo/front-view-male-courier-blue-uniform-with-coffee-blue_179666-29863.jpg?uid=R113681707&ga=GA1.1.1286231835.1732289670&semt=ais_hybrid" style="background-image: url('https://via.placeholder.com/350x200?text=Shipping+Solutions');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Best Shipping Solutions for Small E-Commerce Businesses</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 5, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" data-setbg="https://img.freepik.com/free-vector/flat-design-winter-sale_23-2148715823.jpg?t=st=1738420259~exp=1738423859~hmac=f73e85333c0764da0b0cb9961e5d66bd9ca94f9db6dd774a4641c4963b4d6e31&w=740" style="background-image: url('https://via.placeholder.com/350x200?text=Holiday+Sales');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Maximizing Holiday Sales with Limited-Time Offers</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 2, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" data-setbg="https://img.freepik.com/free-photo/chrysanthemum-flower-against-glass-podium-blue-background_23-2149244158.jpg?t=st=1738420386~exp=1738423986~hmac=18d3df628da2dcc8465667674f1faaf68e3bd8f359900d97232f2e476963533e&w=360" style="background-image: url('https://via.placeholder.com/350x200?text=Product+Photography');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">The Importance of Professional Product Photography</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Dec 28, 2024</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Column 3 -->
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="blog__item">
                <div class="blog__item__pic set-bg" data-setbg="https://img.freepik.com/free-photo/happy-man-holding-email-symbol_53876-42604.jpg?t=st=1738420424~exp=1738424024~hmac=7be27bb33541c9441b617f3a20d288cf102cece0a89d10d8b15200b82e0e5abd&w=740" style="background-image: url('https://via.placeholder.com/350x200?text=Email+Marketing');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Top Email Marketing Tips for E-Commerce Success</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Dec 25, 2024</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" data-setbg="https://img.freepik.com/free-vector/awesome-mobile-software-application-development-concept-mobile-phone-with-big-gear_39422-984.jpg?t=st=1738420500~exp=1738424100~hmac=c5d67659e0a5bb95acfdf56b349837c83fb96b6d65f26f9e90d1ff6d862eca04&w=740" style="background-image: url('https://via.placeholder.com/350x200?text=Mobile+Optimization');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Why Mobile Optimization is Key for E-Commerce in 2025</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Dec 20, 2024</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic large__item set-bg" data-setbg="https://img.freepik.com/free-photo/internet-star-films-shopping-haul_482257-96932.jpg?t=st=1738420545~exp=1738424145~hmac=028e40edad782910cd15e82e21adfcb3d11ec262ac1690734ee7fe5d18016d4e&w=900" style="background-image: url('https://via.placeholder.com/350x200?text=Ecommerce+Success');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">10 Secrets to Building a Successful E-Commerce Brand</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Dec 15, 2024</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Load More Button -->
        <div class="col-lg-12 text-center">
            <a href="#" class="primary-btn load-btn">Load more posts</a>
        </div>
    </div>
</div>

    </section>
<!-- Contact Section End -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@include('components.footer')
</html>

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
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
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
                <div class="blog__item__pic large__item set-bg" style="background-image: url('https://th.bing.com/th/id/OIP.M9wkEtKyioX8ol0VJLct1gHaEK?rs=1&pid=ImgDetMain');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Top 10 E-Commerce Trends to Watch in 2025</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 15, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Discount+Strategies');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">How to Create a Winning Discount Strategy for Your Store</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 10, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Customer+Reviews');"></div>
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
                <div class="blog__item__pic set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Shipping+Solutions');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Best Shipping Solutions for Small E-Commerce Businesses</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 5, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Holiday+Sales');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Maximizing Holiday Sales with Limited-Time Offers</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Jan 2, 2025</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Product+Photography');"></div>
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
                <div class="blog__item__pic set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Email+Marketing');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Top Email Marketing Tips for E-Commerce Success</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Dec 25, 2024</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Mobile+Optimization');"></div>
                <div class="blog__item__text">
                    <h6><a href="#">Why Mobile Optimization is Key for E-Commerce in 2025</a></h6>
                    <ul>
                        <li>by <span>Admin</span></li>
                        <li>Dec 20, 2024</li>
                    </ul>
                </div>
            </div>
            <div class="blog__item">
                <div class="blog__item__pic large__item set-bg" style="background-image: url('https://via.placeholder.com/350x200?text=Ecommerce+Success');"></div>
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

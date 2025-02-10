<div id="toast-container" aria-live="polite" aria-atomic="true">
    <div id="toast" class="toast" style="font-weight: bolder;"></div>
</div>
<script type="text/javascript">
document.querySelectorAll('.view-product-btn').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        window.location.href = '/product-details/' + productId;
    });
});
</script>

<!-- Footer Section Begin -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-7">
                <div class="footer__about">
                    <div  class="footer__logo">
                        <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}" style="height: 150px;" alt=""></a>
                    </div>
                    <p>Connecting buyers and sellers...</p>
                    <div class="footer__payment">
                        <a href="#"><img src="img/payment/payment-1.png" alt=""></a>
                        <a href="#"><img src="img/payment/payment-2.png" alt=""></a>
                        <a href="#"><img src="img/payment/payment-3.png" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-5">
                <div class="footer__widget">
                    <h6>Quick links</h6>
                    <ul>
                        <li><a href="{{ url('about') }}">About</a></li>
                        <li><a href="{{ url('blogs') }}">Blogs</a></li>
                        <li><a href="{{ url('contact') }}">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4">
                <div class="footer__widget">
                    <h6>Account</h6>
                    <ul>
                        <li><a href="{{ url('dashboard') }}">My Account</a></li>
                        <li><a href="{{ url('orders') }}">Orders</a></li>
                        <li><a href="{{ url('checkout') }}">Checkout</a></li>
                        <li><a href="{{ url('wishlist') }}">Wishlist</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="footer__newslatter">
                    @if(session('submessage'))
                                    <div class="alert alert-success text-red-800 bg-red-200 p-4 rounded mb-4">
                                        {{ session('submessage') }}
                                    </div>
                    @endif
                    @if(Session::has('suberror'))
                                    <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4">
                                        {{ Session::get('suberror') }}
                                    </div>
                    @endif
                    <h6>NEWSLETTER</h6>
                    <form action="{{ url('newsletter') }}" method="post">
                        @csrf
                        <input type="text" name="email" placeholder="Email">
                        <button type="submit" class="site-btn">Subscribe</button>
                    </form>
                    <div class="footer__social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                <div class="footer__copyright__text">
                    <p>Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved | Built by AfricTech
                </div>
               
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->

<!-- Search Begin -->
<div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch">+</div>
        <form class="search-model-form" action="{{ url('products') }}" method="GET">
            <input value="{{ request('query') }}"  name="query"  type="text" id="search-input" placeholder="Search here.....">
        </form>
    </div>
</div>
<!-- Search End -->

<!-- Js Plugins -->
<!-- JavaScript Files -->
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
</body>
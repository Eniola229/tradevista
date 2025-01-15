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
                        <a href="{{ url('contact') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Contact</span>
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
                        <div class="contact__address">
                            <h5>Contact info</h5>
                            <ul>
                                <li>
                                    <h6><i class="fa fa-map-marker"></i> Address</h6>
                                    <p>Ibadan Nigeria</p>
                                </li>
                                <li>
                                    <h6><i class="fa fa-phone"></i> Phone</h6>
                                    <p><span>+234 813 261 2077</span></p>
                                </li>
                                <li>
                                    <h6><i class="fa fa-headphones"></i> Support</h6>
                                    <p>tradevista2015@gmail.com</p>
                                </li>
                            </ul>
                        </div>
                        <div class="contact__form">
                                      @if($errors->any())
                                    <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4">
                                        <ul class="list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if(session('error'))
                                    <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if(session('message'))
                                    <div class="alert alert-success text-red-800 bg-red-200 p-4 rounded mb-4">
                                        {{ session('message') }}
                                    </div>
                                @endif
                            <h5>SEND MESSAGE</h5>
                            <form action="{{ url('contact') }}" method="POST">
                                @csrf
                                <input type="text" name="name" placeholder="Name" required>
                                <input type="email" name="email" placeholder="Email" required>
                                <input type="text" name="website" placeholder="Website">
                                <textarea name="message" placeholder="Message" required></textarea>
                                <button type="submit" class="site-btn">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__map">
                        <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253229.13746564323!2d3.7402441220125855!3d7.386887225077452!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10398d77eeff086f%3A0x3b33e0f76e8e04a9!2sIbadan%2C%20Oyo!5e0!3m2!1sen!2sng!4v1736906716335!5m2!1sen!2sng"
                        height="780" style="border:0" allowfullscreen="">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@include('components.footer')
</html>

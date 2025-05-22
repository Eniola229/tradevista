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
                        <span>Verify Your Email</span>
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
                            <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another. (Dont forget the spam folder)</p>
                               <form method="POST" action="{{ route('verification.send') }}">
                                @if (session('status') == 'verification-link-sent')
                                    <div class="mb-4 alert alert-suvccess text-red-800 bg-red-200 p-4 rounded mb-4">
                                        A new verification link has been sent to the email address you provided during registration. (Dont forget the spam folder)
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="mb-4 alert alert-danger text-red-800 bg-red-200 p-4 rounded">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @csrf 
                                  <!-- Submit Button -->
                                    <button type="submit" class="site-btn">Resend Verification Email</button>
                                </form>
                                    <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                     <button type="submit" class="site-btn mt-4" style="background: red;">Log Out</button>
                                </form>
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


@include('components.header')
<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
            <li><a href="#"><span class="icon_heart_alt"></span>
                <div class="tip">2</div>
            </a></li>
            <li><a href="#"><span class="icon_bag_alt"></span>
                <div class="tip">2</div>
            </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="./index.html"><img src="img/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
            <a href="#">Login</a>
            <a href="#">Register</a>
        </div>
    </div>
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
                        <span>Register</span>
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
                            <h5>Create an account</h5>
                               <form method="POST" action="{{ route('register') }}">
                                    @csrf 

                                    <!-- Name Field -->
                                    <input type="text" name="name" placeholder="Name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Email Field -->
                                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                    @error('email')
                                       <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Phone Number Field -->
                                    <input type="number" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                       <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Phone Number Field -->
                                    <input type="email" name="referer" placeholder="Referer Email (Optional)" value="{{ old('referer') }}" id="refererEmail" readonly>
                                    @error('referer')
                                       <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Password Field -->
                                    <input type="password" name="password" placeholder="Password" required>
                                    @error('password')
                                       <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Confirm Password Field -->
                                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                                    @error('password_confirmation')
                                       <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Submit Button -->
                                    <button type="submit" class="site-btn">Register</button>
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
  <script>
    // Function to get query parameter value
    function getQueryParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }

    // Check if 'referer_code' is in the URL and set the input value
    const refererEmail = getQueryParam('referer_code');
    if (refererEmail) {
      document.getElementById('refererEmail').value = refererEmail;
    }
  </script>

@include('components.footer')
</html>

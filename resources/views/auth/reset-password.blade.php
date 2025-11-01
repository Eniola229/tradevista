@include('components.header')
<body>

    <!-- Offcanvas Menu End -->
     @include('components.mobile-nav')
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
                        <span>Reset Password</span>
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
                            <h5>Reset Password</h5>
                               <form method="POST" action="{{ route('password.store') }}">
                                    @csrf 
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                    <!-- Email Field -->
                                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror

                                    <!-- Password Field -->
                                    <input type="password" name="password" placeholder="Password" required>
                                    @error('password')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <!-- Password Field -->
                                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                                    @error('password_confirmation')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <!-- Submit Button -->
                                    <button type="submit" class="site-btn">Reset Password</button>
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


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
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Forgot Password</span>
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
                            <h5>Forgot Password</h5>
                                @if(session('status'))
                                    <div class="alert alert-success text-green-800 bg-green-200 p-4 rounded mb-4">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-error text-red-800 bg-red-200 p-4 rounded mb-4">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4">
                                        <ul class="list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                               <form method="POST" action="{{ route('password.email') }}">
                                    @csrf 
                                    <!-- Email Field -->
                                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                    <!-- Submit Button -->
                                    <button type="submit" class="site-btn">Email Password Reset Link</button>
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

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
                                    <input type="password" name="password" id="password" placeholder="Password" required>
                                    @error('password')
                                       <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Confirm Password Field -->
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                                    @error('password_confirmation')
                                       <div class="alert alert-danger mb-2">{{ $message }}</div>
                                    @enderror

                                    <!-- Feedback Message -->
                                    <div id="password-match-feedback" class="mt-1 small"></div>

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
<script>
  const password = document.getElementById('password');
  const confirm = document.getElementById('password_confirmation');
  const feedback = document.getElementById('password-match-feedback');

  function checkMatch() {
    if (!confirm.value) {
      feedback.textContent = '';
      return;
    }

    if (password.value === confirm.value) {
      feedback.textContent = '✅ Passwords match';
      feedback.className = 'text-success mt-1 small';
    } else {
      feedback.textContent = '❌ Passwords do not match';
      feedback.className = 'text-danger mt-1 small';
    }
  }

  password.addEventListener('input', checkMatch);
  confirm.addEventListener('input', checkMatch);
</script>

@include('components.footer')
</html>

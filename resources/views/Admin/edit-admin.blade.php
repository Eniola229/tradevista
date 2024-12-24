<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

  <title>TradeVista - Admin</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">

  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet">

  <!-- Nepcha Analytics -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
@include('components.admin-sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('components.admin-nav')
    <!-- End Navbar -->
    
     <div class="row">
  <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-11">
              @if(session('message'))
              <div class="alert alert-success alert-dismissible fade show" role="alert" style="color: white;">
                  <strong>Success:</strong> {{ session('message') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

              @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert" style="color: white;">
                  <strong>Error:</strong> {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

              @if($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert" style="color: white;">
                  <strong>Error:</strong> Please fix the following issues:
                  <ul>
                      @foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

                <h3 class="text-center">Edit Admin - {{ $admin->name }}</h3>
                <div class="card p-4">
                    <form action="{{ route('admin-team-add') }}" method="post">
                      @csrf

                      <input type="hidden" value="{{ $admin->id }}" name="id">
                        <!-- First and Last Name -->
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label for="fname" class="form-label">Full name<span class="text-danger"> *</span></label>
                                <input type="text" value="{{ $admin->name }}" class="form-control" id="fname" name="name" placeholder="Enter full name">
                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="form-label">Email<span class="text-danger"> *</span></label>
                                <input type="email" value="{{ $admin->email }}" class="form-control" id="email" name="email" placeholder="Enter email">
                            </div>
                        </div>

                        <!-- Email and Phone Number -->
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label for="mob" class="form-label">Phone number<span class="text-danger"> *</span></label>
                                <input type="text" value="{{ $admin->mobile }}" class="form-control" id="mob" name="mobile" placeholder="Enter phone number">
                            </div>
                            <div class="col-sm-6">
                                <label for="Password" class="form-label">Password<span class="text-danger"> *</span></label>
                                <input type="Password" class="form-control" id="Password" name="password" placeholder="Enter Password">
                            </div>
                        </div>

                      <div class="col-sm-6">
                      <label for="options" class="form-label">Choose a role<span class="text-danger"> *</span></label>
                      <select id="options" name="options" class="form-select" required>
                          <option value="" selected disabled>Select a role</option>
                          <option value="SUPER">SUPER</option>
                          <option value="ADMIN">ADMIN</option>
                          <option value="SUPPORT">SUPPORT</option>
                          <option value="ACCOUNT">ACCOUNT</option>
                          <option value="MANAGER">MANAGER</option>
                      </select>
                  </div>


                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-sm-6 ms-auto">
                                <button type="submit" class="btn btn-primary w-100">Edit Admin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
      </div>
    </div>
  </main>
  <!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js') }}?v={{ time() }}"></script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>TradeVista - {{ Auth::user()->name }}</title>

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
@include('components.sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('components.nav')
      
    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

         <div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-12 col-7">
                   <div class="d-flex flex-column flex-md-row align-items-center justify-content-between rounded gap-3">
                    <h2 class="h5 text-dark mb-0">Your Referers</h2>
                    <button class="btn btn-primary" id="copyButton">Copy referer link</button>
                  </div>
                 
                    <p style="display: none;" class="alert alert-info text-white" id="textToCopy">https://tradevista.biz/register?referer_code={{ Auth::user()->email }}</p>
                   
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Referer Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Referer Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Referer Mobile No</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date Added</th>
                      </tr>
                  </thead>
                  <tbody>
                    @if($referers->isNotEmpty())
                    @foreach($referers as $referered)
                        <tr>
                            <td>
                                <h6 class="mb-0 text-sm">{{ $referered->referer->name }}</h6>
                            </td>
                            <td>
                                <h6 class="mb-0 text-sm">{{ $referered->referer->email }}</h6>
                            </td>
                            <td>
                                <h6 class="mb-0 text-sm">{{ $referered->referer->phone_number }}</h6>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $referered->created_at->format('F j, Y g:i A') }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">
                            <p>You dont have any referer yet.</p>
                        </td>
                    </tr>
                @endif

                  </tbody>
                </table>
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
    document.getElementById("copyButton").addEventListener("click", function () {
      // Get the text inside the <p> tag
      const text = document.getElementById("textToCopy").innerText;

      // Create a temporary textarea element to copy the text
      const textarea = document.createElement("textarea");
      textarea.value = text;
      document.body.appendChild(textarea);

      // Select and copy the text
      textarea.select();
      document.execCommand("copy");

      // Remove the temporary textarea
      document.body.removeChild(textarea);

      // Notify the user
      alert("Link copied to clipboard!");
    });
  </script>
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
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
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Account Balance</p>
                    <h5 class="font-weight-bolder mb-0">
                   
                     @php
                    $balance = Auth::user()->balance;
                    @endphp 

                    @if ($balance > 0)
                        ₦{{ number_format($balance, 2) }}
                    @elseif ($balance < 0)
                        ₦{{ number_format($balance, 2) }}
                    @else
                       ₦0.00
                    @endif
                      <!-- <span class="text-success text-sm font-weight-bolder">+55%</span> -->
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
         @if($setup)
          @if($setup->account_type == 'SELLER')
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Products</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $productCount }}
                      <!-- <span class="text-success text-sm font-weight-bolder">+3%</span> -->
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif
        @endif
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending orders</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $pendingOrder }}
                      <!-- <span class="text-danger text-sm font-weight-bolder">-2%</span> -->
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders</p>
                    <h5 class="font-weight-bolder mb-0">
                     {{ $DeliveredOrder }}
                      <!-- <span class="text-success text-sm font-weight-bolder">+5%</span> -->
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
         @if($errors->any())
            <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4 mt-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                         <li style="Color: white;">{{ $error }}</li>
                             @endforeach
                         </ul>
               </div>
         @endif
         @if(session('error'))
             <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4 mt-4" style="color: white;">
                 {{ session('error') }}
             </div>
        @endif
             @if(session('message'))
             <div class="alert alert-success text-red-800 bg-red-200 p-4 rounded mb-4 mt-4" style="Color: white">
                   {{ session('message') }}
            </div>
         @endif
      @if($setup)
      @if($pendingPayment) 
             <div class="alert alert-warning text-red-800 bg-red-200 p-4 rounded mb-4 mt-4" style="Color: white">
                Your Payment is still <strong>PENDING.</strong> We advise you contact our support team and your bank if you have been debited . <strong>Thanks</strong>
            </div>
      @else
     @if($setup->account_type == 'SELLER')
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mt-4 p-4 rounded shadow-sm gap-3">
        <h2 class="h5 text-dark mb-0">Upload a new product</h2>
        <a href="{{ url('add-edit-product') }}">
          <button class="btn btn-primary">Click here</button>
        </a>
      </div>
      @endif
      @endif
      @else
      <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
          <div class="card p-4">
        <form
        id="accountForm"
        action="{{ route('create.setup') }}"
        method="POST"
        class=""
        enctype="multipart/form-data"
    >
        @csrf
        <h3 class="font-bold text-2xl mb-4 text-gray-800">Complete you Account Set up</h3>

        <!-- Account Type Dropdown -->
        <div class="mb-4">
            <label for="account_type" class="block text-sm font-medium text-gray-700">Select Account Type</label> <br>
            <select
                name="account_type"
                id="account_type"
                class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700"
                required
                style="width: 100%;"
            >
                <option value="" disabled selected>Select an option</option>
                <option value="BUYER">BUYER</option>
                <option value="SELLER">SELLER</option>
            </select>
        </div>

        <!-- Seller Fields -->
        <div id="seller-fields" class="hidden">
            <div class="mb-4">
                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label><br>
                <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    style="width: 100%;"
                    value="{{ old('company_name') }}"
                >
            </div>

            <div class="mb-4">
                <label for="company_description" class="block text-sm font-medium text-gray-700">Company Description</label><br>
                <textarea
                    id="company_description"
                    name="company_description"
                    rows="3"
                    class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    style="width: 100%;"
                >{{ old('company_description') }}</textarea>
            </div>

      <div class="mb-4">
          <label for="state" class="block text-sm font-medium text-gray-700">State</label><br>
          <select
              id="state"
              name="state"
              class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              style="width: 100%;"
          >
              <option value="">Select State</option>
              <option value="Abia" {{ old('state') == 'Abia' ? 'selected' : '' }}>Abia</option>
              <option value="Adamawa" {{ old('state') == 'Adamawa' ? 'selected' : '' }}>Adamawa</option>
              <option value="Akwa Ibom" {{ old('state') == 'Akwa Ibom' ? 'selected' : '' }}>Akwa Ibom</option>
              <option value="Anambra" {{ old('state') == 'Anambra' ? 'selected' : '' }}>Anambra</option>
              <option value="Bauchi" {{ old('state') == 'Bauchi' ? 'selected' : '' }}>Bauchi</option>
              <option value="Bayelsa" {{ old('state') == 'Bayelsa' ? 'selected' : '' }}>Bayelsa</option>
              <option value="Benue" {{ old('state') == 'Benue' ? 'selected' : '' }}>Benue</option>
              <option value="Borno" {{ old('state') == 'Borno' ? 'selected' : '' }}>Borno</option>
              <option value="Cross River" {{ old('state') == 'Cross River' ? 'selected' : '' }}>Cross River</option>
              <option value="Delta" {{ old('state') == 'Delta' ? 'selected' : '' }}>Delta</option>
              <option value="Ebonyi" {{ old('state') == 'Ebonyi' ? 'selected' : '' }}>Ebonyi</option>
              <option value="Edo" {{ old('state') == 'Edo' ? 'selected' : '' }}>Edo</option>
              <option value="Ekiti" {{ old('state') == 'Ekiti' ? 'selected' : '' }}>Ekiti</option>
              <option value="Enugu" {{ old('state') == 'Enugu' ? 'selected' : '' }}>Enugu</option>
              <option value="Gombe" {{ old('state') == 'Gombe' ? 'selected' : '' }}>Gombe</option>
              <option value="Imo" {{ old('state') == 'Imo' ? 'selected' : '' }}>Imo</option>
              <option value="Jigawa" {{ old('state') == 'Jigawa' ? 'selected' : '' }}>Jigawa</option>
              <option value="Kaduna" {{ old('state') == 'Kaduna' ? 'selected' : '' }}>Kaduna</option>
              <option value="Kano" {{ old('state') == 'Kano' ? 'selected' : '' }}>Kano</option>
              <option value="Katsina" {{ old('state') == 'Katsina' ? 'selected' : '' }}>Katsina</option>
              <option value="Kebbi" {{ old('state') == 'Kebbi' ? 'selected' : '' }}>Kebbi</option>
              <option value="Kogi" {{ old('state') == 'Kogi' ? 'selected' : '' }}>Kogi</option>
              <option value="Kwara" {{ old('state') == 'Kwara' ? 'selected' : '' }}>Kwara</option>
              <option value="Lagos" {{ old('state') == 'Lagos' ? 'selected' : '' }}>Lagos</option>
              <option value="Nasarawa" {{ old('state') == 'Nasarawa' ? 'selected' : '' }}>Nasarawa</option>
              <option value="Niger" {{ old('state') == 'Niger' ? 'selected' : '' }}>Niger</option>
              <option value="Ogun" {{ old('state') == 'Ogun' ? 'selected' : '' }}>Ogun</option>
              <option value="Ondo" {{ old('state') == 'Ondo' ? 'selected' : '' }}>Ondo</option>
              <option value="Osun" {{ old('state') == 'Osun' ? 'selected' : '' }}>Osun</option>
              <option value="Oyo" {{ old('state') == 'Oyo' ? 'selected' : '' }}>Oyo</option>
              <option value="Plateau" {{ old('state') == 'Plateau' ? 'selected' : '' }}>Plateau</option>
              <option value="Rivers" {{ old('state') == 'Rivers' ? 'selected' : '' }}>Rivers</option>
              <option value="Sokoto" {{ old('state') == 'Sokoto' ? 'selected' : '' }}>Sokoto</option>
              <option value="Taraba" {{ old('state') == 'Taraba' ? 'selected' : '' }}>Taraba</option>
              <option value="Yobe" {{ old('state') == 'Yobe' ? 'selected' : '' }}>Yobe</option>
              <option value="Zamfara" {{ old('state') == 'Zamfara' ? 'selected' : '' }}>Zamfara</option>
              <option value="FCT" {{ old('state') == 'FCT' ? 'selected' : '' }}>FCT</option>
          </select>
      </div>


            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label><br>
                <input
                    type="text"
                    id="address"
                    name="address"
                    class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    style="width: 100%;"
                     value="{{ old('address') }}"
                >
            </div>

            <div class="mb-4">
                <label for="zipcode" class="block text-sm font-medium text-gray-700">Zip Code </label><br>
                <input
                    type="text"
                    id="zipcode"
                    name="zipcode"
                    class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    style="width: 100%;"
                     value="{{ old('zipcode') }}"
                >
            </div>

            <div class="mb-4">
                <label for="company_mobile_1" class="block text-sm font-medium text-gray-700">Mobile Number 1</label><br>
                <input
                    type="text"
                    id="company_mobile_1"
                    name="company_mobile_1"
                    class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    style="width: 100%;"
                     value="{{ old('company_mobile_1') }}"
                >
            </div>

            <div class="mb-4">
                <label for="company_mobile_2" class="block text-sm font-medium text-gray-700">Mobile Number 2</label><br>
                <input
                    type="text"
                    id="company_mobile_2"
                    name="company_mobile_2"
                    class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    style="width: 100%;"
                     value="{{ old('company_mobile_2') }}"
                >
            </div>

            <div class="mb-4">
                <label for="company_image" class="block text-sm font-medium text-gray-700">Company Image</label><br>
                <input
                    type="file"
                    id="company_image"
                    name="company_image"
                    class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    style="width: 100%; border: 2px solid black;"

                >
            </div>
        </div>

        <button
            type="submit"
            id="submitButton"
            class="btn btn-primary"
        >
            Submit
        </button>
    </form>

 

          </div>
        </div>
        <div class="col-lg-5">
          <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('../assets/img/ivancik.jpg');">
              <span class="mask bg-gradient-dark"></span>
              <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                <h5 class="text-white font-weight-bolder mb-4 pt-2">Complete Setting up your account</h5>
                <p class="text-white">By seting up your account you choose, between being a<strong>SELLER</strong> or <strong>BUYER</strong></p>
                <a class="text-white text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="javascript:;">
                  By being a <strong>SELLER</strong> you have to pay a sum of <strong>₦2,875</strong> for acount set up
                  <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mt-4 p-4 rounded shadow-sm gap-3">
        <h2 class="h5 text-dark mb-0">Refer a friend and earn ₦100</h2>
        <p class="alert alert-info text-white" id="textToCopy">https://tradevista.biz/register?referer_code={{ Auth::user()->email }}</p>
          <button class="btn btn-primary" id="copyButton">Copy link</button>
      </div>
      <div class="row my-4">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Payment History</h6>
                
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Null</a></li>
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
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Description</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                              @foreach($payments as $payment)
                    <tr>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">{{ $payment->description }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <span class="text-xs font-weight-bold">
                                @php
                                    $balance = $payment->amount;
                                @endphp

                                @if ($balance > 0)
                                    ₦{{ number_format($balance, 2) }}
                                @elseif ($balance < 0)
                                    ₦{{ number_format($balance, 2) }}
                                @else
                                    ₦0.00
                                @endif
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="progress-wrapper w-75 mx-auto">
                                <div class="progress-info">
                                    <div class="progress-percentage">
                                        <span class="text-xs font-weight-bold">{{ $payment->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">{{ $payment->created_at->format('F j, Y g:i A') }}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <button class="btn btn-primary">View</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                    <div class="d-flex justify-content-center mt-3">
                        <ul class="pagination">
                            @if ($payments->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">«</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $payments->previousPageUrl() }}">«</a></li>
                            @endif

                            @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                                <li class="page-item {{ $payments->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            @if ($payments->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $payments->nextPageUrl() }}"> »</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link"> »</span></li>
                            @endif
                        </ul>
                    </div>
              </div>
            </div>
          </div>
        </div>
      <!--   <div class="col-lg-4 col-md-6">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6>Notification</h6>
            </div>
            <div class="card-body p-3">
              <div class="timeline timeline-one-side">
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-bell-55 text-success text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 7:20 PM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-html5 text-danger text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">New order #1832412</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 11 PM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-cart text-info text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Server payments for April</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 9:34 PM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-credit-card text-warning text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">New card added for order #4395133</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">20 DEC 2:20 AM</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-key-25 text-primary text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Unlock packages for development</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">18 DEC 4:54 AM</p>
                  </div>
                </div>
                <div class="timeline-block">
                  <span class="timeline-step">
                    <i class="ni ni-money-coins text-dark text-gradient"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">New order #9583120</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">17 DEC</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </main>
  <!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <!-- JavaScript -->
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
        document.addEventListener('DOMContentLoaded', function () {
            const accountTypeSelect = document.getElementById('account_type');
            const sellerFields = document.getElementById('seller-fields');

            // Hide seller fields by default
            sellerFields.style.display = 'none';

            accountTypeSelect.addEventListener('change', function () {
                if (this.value === 'SELLER') {
                    sellerFields.style.display = 'block';
                    sellerFields.querySelectorAll('input, textarea').forEach(input => {
                        input.setAttribute('required', true);
                    });
                } else {
                    sellerFields.style.display = 'none';
                    sellerFields.querySelectorAll('input, textarea').forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            });
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
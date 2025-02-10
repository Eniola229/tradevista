<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

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

    <!-- End Navbar -->
    <div class="main">
      <main class="content">
        <div class="container-fluid p-0">

          <div class="mb-3">
            <h1 class="h3 d-inline align-middle p-4">Profile</h1>
            <!-- <a class="badge bg-dark text-white ms-2" href="upgrade-to-pro.html">
              Get more page examples
            </a> -->
          </div>
          <div class="row">
            <div class="col-md-4 col-xl-3">
              <div class="card mb-3">
                <div class="card-header">
                  <h5 class="card-title mb-0">Profile Details</h5>
                </div>
                <div class="card-body text-center">
                  <div class="text-center">
                    <!-- Avatar -->
                    <img 
                        src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/150' }}" 
                        alt="User Avatar" 
                        class="img-fluid rounded-circle mb-2" 
                        width="128" 
                        height="128" 
                        id="user-avatar"
                    />

                    <!-- Edit Icon -->
                    <button 
                        class="btn btn-sm btn-primary mt-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editAvatarModal">
                        <i class="bi bi-pencil"></i> Edit Avatar
                    </button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="editAvatarModal" tabindex="-1" aria-labelledby="editAvatarModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAvatarModalLabel">Update Avatar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('avatar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Choose an Image</label>
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="avatar" 
                                            name="avatar" 
                                            accept="image/*" 
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload Avatar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                  <h5 class="card-title mb-0">{{ Auth::user()->name }}</h5>
                  <div>
                    <a class="btn btn-primary btn-sm" href="#">{{ $setup->account_type }}</a>
                    <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span>N {{ number_format(Auth::user()->balance, 2) }}</a>
                  </div>
                </div>
                <hr class="my-0" />
              </div>
            </div>

            <div class="col-md-8 col-xl-9">
            <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs justify-content-center" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="true">Account Type</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist" type="button" role="tab" aria-controls="wishlist" aria-selected="false">Address</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Update Proflle</button>
                    </li>
                </ul>
                <div class="tab-content mt-4 p-4" id="profileTabsContent">
                    <!-- Orders Tab -->
                    <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="">
                           <form
                                id="accountForm"
                                action="{{ route('update.setup', $setup->id) }}"
                                method="POST"
                                class=""
                                enctype="multipart/form-data"
                            >
                                @csrf
                                @method('PUT')
                                <h3 class="font-bold text-2xl mb-4 text-gray-800">Update Your Account Setup</h3>

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
                                        <option value="" disabled>Select an option</option>
                                        <option value="BUYER" {{ old('account_type', $setup->account_type) === 'BUYER' ? 'selected' : '' }}>BUYER</option>
                                        <option value="SELLER" {{ old('account_type', $setup->account_type) === 'SELLER' ? 'selected' : '' }}>SELLER</option>
                                    </select>
                                </div>

                                <!-- Seller Fields -->
                                <div id="seller-fields" style="display: {{ old('account_type', $setup->account_type) === 'SELLER' ? 'block' : 'none' }}">
                                    <div class="mb-4">
                                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label><br>
                                        <input
                                            type="text"
                                            id="company_name"
                                            name="company_name"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                            value="{{ old('company_name', $setup->company_name) }}"
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
                                        >{{ old('company_description', $setup->company_description) }}</textarea>
                                    </div>

                                        <div class="mb-4">
                                            <label for="state" class="block text-sm font-medium text-gray-700">Address Line 1</label><br>
                                            <select
                                                id="state"
                                                name="state"
                                                class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                style="width: 100%;"
                                            >
                                                <option value="">Select State</option>
                                                <option value="Abia">Abia</option>
                                                <option value="Adamawa">Adamawa</option>
                                                <option value="Akwa Ibom">Akwa Ibom</option>
                                                <option value="Anambra">Anambra</option>
                                                <option value="Bauchi">Bauchi</option>
                                                <option value="Bayelsa">Bayelsa</option>
                                                <option value="Benue">Benue</option>
                                                <option value="Borno">Borno</option>
                                                <option value="Cross River">Cross River</option>
                                                <option value="Delta">Delta</option>
                                                <option value="Ebonyi">Ebonyi</option>
                                                <option value="Edo">Edo</option>
                                                <option value="Ekiti">Ekiti</option>
                                                <option value="Enugu">Enugu</option>
                                                <option value="Gombe">Gombe</option>
                                                <option value="Imo">Imo</option>
                                                <option value="Jigawa">Jigawa</option>
                                                <option value="Kaduna">Kaduna</option>
                                                <option value="Kano">Kano</option>
                                                <option value="Katsina">Katsina</option>
                                                <option value="Kebbi">Kebbi</option>
                                                <option value="Kogi">Kogi</option>
                                                <option value="Kwara">Kwara</option>
                                                <option value="Lagos">Lagos</option>
                                                <option value="Nasarawa">Nasarawa</option>
                                                <option value="Niger">Niger</option>
                                                <option value="Ogun">Ogun</option>
                                                <option value="Ondo">Ondo</option>
                                                <option value="Osun">Osun</option>
                                                <option value="Oyo">Oyo</option>
                                                <option value="Plateau">Plateau</option>
                                                <option value="Rivers">Rivers</option>
                                                <option value="Sokoto">Sokoto</option>
                                                <option value="Taraba">Taraba</option>
                                                <option value="Yobe">Yobe</option>
                                                <option value="Zamfara">Zamfara</option>
                                                <option value="FCT">FCT</option>
                                            </select>
                                        </div>

                        
                                    <div class="mb-4">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Address Line 1</label><br>
                                        <input
                                            type="text"
                                            id="address"
                                            name="address"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                            value="{{ old('address', $setup->address) }}"
                                        >
                                    </div>

                                    <!-- Repeat for all seller-specific fields, preloading $setup data -->
                                    <div class="mb-4">
                                        <label for="zipcode" class="block text-sm font-medium text-gray-700">Address Line 1</label><br>
                                        <input
                                            type="text"
                                            id="zipcode"
                                            name="zipcode"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                            value="{{ old('zipcode', $setup->zipcode) }}"
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
                                        <small class="text-gray-600">Current Image: <a href="{{ $setup->company_image }}" target="_blank">View</a></small>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    id="submitButton"
                                    class="btn btn-primary"
                                >
                                    Update
                                </button>
                            </form>

                        </div>
                    </div>

                    <!-- Wishlist Tab -->
                    <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                    <div class="card p-4">
                   @if($shippingAddress)
                       <div class="container mt-5">
                          <h2 class="mb-4">Address</h2>
                          <table class="table table-bordered table-striped">
                              <thead>
                                  <tr>
                                      <th>Country</th>
                                      <th>State</th>
                                      <th>Town/City</th>
                                      <th>Address</th>
                                      <th>ZIP Code</th>
                                      <th>Address Type</th>
                                      <th>Actions</th>
                                  </tr>
                              </thead>
                              <tbody>
                                      <tr>
                                          <td>{{ $shippingAddress->country }}</td>
                                          <td>{{ $shippingAddress->state }}</td>
                                          <td>{{ $shippingAddress->town_city }}</td>
                                          <td>{{ $shippingAddress->address }}</td>
                                          <td>{{ $shippingAddress->zip }}</td>
                                          <td>{{ ucfirst($shippingAddress->address_type) }}</td>
                                          <td>
                                               <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addressModal">Edit</button>
                                          </td>
                                      </tr>
                                 
                              </tbody>
                          </table>
                      </div>

                          <!-- Modal -->
                        <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addressModalLabel">Edit Address</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('address', $shippingAddress->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <!-- User ID -->
                                            <input type="hidden" class="form-control" id="userId" value="{{ Auth::user()->id }}" name="user_id" placeholder="Enter User ID" required>

                                            <!-- Country -->
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <select class="form-select" id="country" name="country" required>
                                                    <option value="Nigeria" selected>Nigeria</option>
                                                </select>
                                            </div>

                                              <!-- State -->
                                                <div class="mb-3">
                                                <label for="state" class="form-label">State</label>
                                                <select class="form-select" id="state" name="state" required>
                                                    <option value="" disabled selected>Select State</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->name }}" {{ old('state') == $state->name ? 'selected' : '' }}>
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                </div>

                                            <!-- Town/City -->
                                            <div class="mb-3">
                                                <label for="townCity" class="form-label">Town/City</label>
                                                <select id="city" name="town_city" class="form-select">
                                                    <option value="">Select a city</option>
                                                </select>
                                            </div>
                                            <!-- Address -->
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" value="{{ $shippingAddress->address }}" class="form-control" id="address" name="address" placeholder="Enter Address" required>
                                            </div>

                                            <!-- ZIP -->
                                            <div class="mb-3">
                                                <label for="zip" class="form-label">ZIP Code</label>
                                                <input type="text" value="{{ $shippingAddress->town_city }}" class="form-control" id="zip" name="zip" placeholder="Enter ZIP Code" required>
                                            </div>

                                            <!-- Address Type -->
                                            <div class="mb-3">
                                                <label for="address_type" class="form-label">Address Type</label>
                                                <select class="form-select" id="address_type" name="address_type" required>
                                                    <option value="" disabled selected>Select Address Type</option>
                                                    <option value="home">Home</option>
                                                    <option value="work">Work</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Address</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                   @else
                  <form action="{{ url('address') }}" method="POST">
                    @csrf
                    <!-- User ID -->
                    <input type="hidden" class="form-control" id="userId" value="{{ Auth::user()->id }}" name="user_id" placeholder="Enter User ID" required>
                   <!-- Country -->
                  <div class="mb-3">
                      <label for="country" class="form-label">Country</label>
                      <select class="form-select" id="country" name="country" required>
                          <option value="Nigeria" selected>Nigeria</option>
                      </select>
                  </div>

                  <!-- State -->
                  <div class="mb-3">
                                                <label for="state" class="form-label">State</label>
                                                <select class="form-select" id="state" name="state" required>
                                                    <option value="" disabled selected>Select State</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->name }}" {{ old('state') == $state->name ? 'selected' : '' }}>
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                </div>

                    <!-- Town/City -->
                    <div class="mb-3">
                        <label for="townCity" class="form-label">Town/City</label>
                        <select id="city" name="town_city" class="form-select">
                            <option value="">Select a city</option>
                        </select>
                    </div>

                                            <!-- Address -->
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" required>
                                            </div>

                    <!-- ZIP -->
                    <div class="mb-3">
                        <label for="zip" class="form-label">ZIP Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" placeholder="Enter ZIP Code" required>
                    </div>

                    <!-- Address Type -->
                    <div class="mb-3">
                        <label for="addressType" class="form-label">Address Type</label>
                        <select class="form-select" id="addressType" name="address_type" required>
                            <option value="" disabled selected>Select Address Type</option>
                            <option value="home">Home</option>
                            <option value="work">Work</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                @endif
                        </div>
                    </div>
                     <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                      <div class="container mt-5">
                      <h2>Update Profile</h2>
                      <form action="{{ route('profile.update') }}" method="POST">
                          @csrf

                          <!-- Name -->
                          <div class="mb-3">
                              <label for="name" class="form-label">Name</label>
                              <input 
                                  type="text" 
                                  class="form-control" 
                                  id="name" 
                                  name="name" 
                                  value="{{ old('name', Auth::user()->name) }}" 
                                  required>
                          </div>

                          <!-- Email -->
                          <div class="mb-3">
                              <label for="email" class="form-label">Email</label>
                              <input 
                                  type="email" 
                                  class="form-control" 
                                  id="email" 
                                  name="email" 
                                  value="{{ old('email', Auth::user()->email) }}" 
                                  required>
                          </div>

                          <!-- Phone Number -->
                          <div class="mb-3">
                              <label for="phone_number" class="form-label">Phone Number</label>
                              <input 
                                  type="text" 
                                  class="form-control" 
                                  id="phone_number" 
                                  name="phone_number" 
                                  value="{{ old('phone_number', Auth::user()->phone_number) }}" 
                                  required>
                          </div>

                          <!-- Password -->
                          <div class="mb-3">
                              <label for="password" class="form-label">Password</label>
                              <input 
                                  type="password" 
                                  class="form-control" 
                                  id="password" 
                                  name="password" 
                                  placeholder="Enter new password (optional)">
                              <small class="form-text text-muted">Leave blank if you don't want to change your password.</small>
                          </div>

                          <button type="submit" class="btn btn-primary">Update Profile</button>
                      </form>
                  </div>

                     </div>
                </div>

          </div>

        </div>
      </main>
    </div>
  </main>
 
  <!--   Core JS Files   -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <!-- JavaScript -->
<script>
    // Handle state change to fetch cities
    document.getElementById('state').addEventListener('change', function () {
        const stateName = this.value; // Get the selected state name
        const cityDropdown = document.getElementById('city');
        cityDropdown.innerHTML = '<option value="">Loading...</option>';

        if (stateName) {
            fetch(`/get-cities?state_name=${stateName}`)
                .then(response => response.json())
                .then(data => {
                    cityDropdown.innerHTML = '<option value="">Select a city</option>';
                    data.forEach(city => {
                        cityDropdown.innerHTML += `<option value="${city.name}">${city.name}</option>`;
                    });
                });
        } else {
            cityDropdown.innerHTML = '<option value="">Select a city</option>';
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const accountTypeSelect = document.getElementById('account_type');
        const sellerFields = document.getElementById('seller-fields');

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
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          },
          {
            label: "Websites",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#3A416F",
            borderWidth: 3,
            backgroundColor: gradientStroke2,
            fill: true,
            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
            maxBarThickness: 6
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
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
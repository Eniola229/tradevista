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
  <script defer data-site="www.tradevista.biz" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <!--csrf token-->
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="g-sidenav-show  bg-gray-100">
@include('components.sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('components.nav')
        <style>
    /* Loading spinner animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .spinner-border {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        vertical-align: text-bottom;
        border: 0.2em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spin 0.75s linear infinite;
    }

    /* Disabled button state */
    .btn:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    /* Shipping method cards */
    .shipping-method-card {
        transition: all 0.3s ease;
    }

    .shipping-method-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    #addressSuggestions {
        display: none;
        max-height: 200px;
        overflow-y: auto;
        cursor: pointer;
    }

    #addressSuggestions li:hover {
        background-color: #f1f1f1;
    }

    .input-error {
        border: 1px solid red !important;
    }
</style>

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
                    <a class="btn btn-primary btn-sm" href="#">{{ $setup?->account_type ?? 'N/A' }}</a>
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
                    @if($setup)
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

                                <p style="color: red; font-size: 13px;"><strong>Important Notice</strong> If you've recently set up your account for the first time and made a successful payment, but your account hasn't been updated, please try updating again. You won't need to make another payment. <br>Thank you for your patience and cooperation.</p>

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
                                        required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="company_description" class="block text-sm font-medium text-gray-700">Company Description</label><br>
                                        <textarea
                                            id="company_description"
                                            name="company_description"
                                            rows="3"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                        required>{{ old('company_description', $setup->company_description) }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="state" class="block text-sm font-medium text-gray-700">State</label><br>
                                        <select
                                            id="toState"
                                            name="state"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                        >
                                            <option value="">Select State</option>
                                            @php
                                                $selectedState = old('state', $setup->state);
                                            @endphp
                                            <option value="Abia" {{ $selectedState == 'Abia' ? 'selected' : '' }}>Abia</option>
                                            <option value="Adamawa" {{ $selectedState == 'Adamawa' ? 'selected' : '' }}>Adamawa</option>
                                            <option value="Akwa Ibom" {{ $selectedState == 'Akwa Ibom' ? 'selected' : '' }}>Akwa Ibom</option>
                                            <option value="Anambra" {{ $selectedState == 'Anambra' ? 'selected' : '' }}>Anambra</option>
                                            <option value="Bauchi" {{ $selectedState == 'Bauchi' ? 'selected' : '' }}>Bauchi</option>
                                            <option value="Bayelsa" {{ $selectedState == 'Bayelsa' ? 'selected' : '' }}>Bayelsa</option>
                                            <option value="Benue" {{ $selectedState == 'Benue' ? 'selected' : '' }}>Benue</option>
                                            <option value="Borno" {{ $selectedState == 'Borno' ? 'selected' : '' }}>Borno</option>
                                            <option value="Cross River" {{ $selectedState == 'Cross River' ? 'selected' : '' }}>Cross River</option>
                                            <option value="Delta" {{ $selectedState == 'Delta' ? 'selected' : '' }}>Delta</option>
                                            <option value="Ebonyi" {{ $selectedState == 'Ebonyi' ? 'selected' : '' }}>Ebonyi</option>
                                            <option value="Edo" {{ $selectedState == 'Edo' ? 'selected' : '' }}>Edo</option>
                                            <option value="Ekiti" {{ $selectedState == 'Ekiti' ? 'selected' : '' }}>Ekiti</option>
                                            <option value="Enugu" {{ $selectedState == 'Enugu' ? 'selected' : '' }}>Enugu</option>
                                            <option value="Gombe" {{ $selectedState == 'Gombe' ? 'selected' : '' }}>Gombe</option>
                                            <option value="Imo" {{ $selectedState == 'Imo' ? 'selected' : '' }}>Imo</option>
                                            <option value="Jigawa" {{ $selectedState == 'Jigawa' ? 'selected' : '' }}>Jigawa</option>
                                            <option value="Kaduna" {{ $selectedState == 'Kaduna' ? 'selected' : '' }}>Kaduna</option>
                                            <option value="Kano" {{ $selectedState == 'Kano' ? 'selected' : '' }}>Kano</option>
                                            <option value="Katsina" {{ $selectedState == 'Katsina' ? 'selected' : '' }}>Katsina</option>
                                            <option value="Kebbi" {{ $selectedState == 'Kebbi' ? 'selected' : '' }}>Kebbi</option>
                                            <option value="Kogi" {{ $selectedState == 'Kogi' ? 'selected' : '' }}>Kogi</option>
                                            <option value="Kwara" {{ $selectedState == 'Kwara' ? 'selected' : '' }}>Kwara</option>
                                            <option value="Lagos" {{ $selectedState == 'Lagos' ? 'selected' : '' }}>Lagos</option>
                                            <option value="Nasarawa" {{ $selectedState == 'Nasarawa' ? 'selected' : '' }}>Nasarawa</option>
                                            <option value="Niger" {{ $selectedState == 'Niger' ? 'selected' : '' }}>Niger</option>
                                            <option value="Ogun" {{ $selectedState == 'Ogun' ? 'selected' : '' }}>Ogun</option>
                                            <option value="Ondo" {{ $selectedState == 'Ondo' ? 'selected' : '' }}>Ondo</option>
                                            <option value="Osun" {{ $selectedState == 'Osun' ? 'selected' : '' }}>Osun</option>
                                            <option value="Oyo" {{ $selectedState == 'Oyo' ? 'selected' : '' }}>Oyo</option>
                                            <option value="Plateau" {{ $selectedState == 'Plateau' ? 'selected' : '' }}>Plateau</option>
                                            <option value="Rivers" {{ $selectedState == 'Rivers' ? 'selected' : '' }}>Rivers</option>
                                            <option value="Sokoto" {{ $selectedState == 'Sokoto' ? 'selected' : '' }}>Sokoto</option>
                                            <option value="Taraba" {{ $selectedState == 'Taraba' ? 'selected' : '' }}>Taraba</option>
                                            <option value="Yobe" {{ $selectedState == 'Yobe' ? 'selected' : '' }}>Yobe</option>
                                            <option value="Zamfara" {{ $selectedState == 'Zamfara' ? 'selected' : '' }}>Zamfara</option>
                                            <option value="FCT" {{ $selectedState == 'FCT' ? 'selected' : '' }}>FCT</option>
                                        </select>
                                    </div>

                                    <!-----Hidden because of validating address---->
                                     <input type="hidden" name="first_name" id="firstname" value="{{ explode(' ', Auth::user()->name)[0] }}">
                                     <input type="hidden" name="last_name" id="lastname" value="{{ count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name)[1] : 'null' }}" required>
                                      <input type="hidden" name="email" value="{{ Auth::user()->email }}" required>

                                    <div class="mb-4">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label><br>
                                        <input
                                            type="text"
                                            id="toAddress"
                                            name="address"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                            value="{{ old('address', $setup->address) }}"
                                        required>
                                        <ul id="addressSuggestions" class="list-group position-absolute w-80" style="z-index: 1000;"></ul>
                                    </div>

                                    <!-- Repeat for all seller-specific fields, preloading $setup data -->
                                    <div class="mb-4">
                                        <label for="zipcode" class="block text-sm font-medium text-gray-700">Zip Code</label><br>
                                        <input
                                            type="readonly"
                                            id="postal"
                                            name="zipcode"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                            value="{{ old('zipcode', $setup->zipcode) }}"
                                        required>
                                    </div>

                                    
                                    <div class="mb-4">
                                        <label for="company_mobile_1" class="block text-sm font-medium text-gray-700">Mobile 1</label><br>
                                        <input
                                            type="number"
                                            id="phone"
                                            name="company_mobile_1"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                            value="{{ old('company_mobile_1', $setup->company_mobile_1) }}"
                                        required>
                                    </div>

                                    
                                    <div class="mb-4">
                                        <label for="company_mobile_2" class="block text-sm font-medium text-gray-700">Mobile 2</label><br>
                                        <input
                                            type="number"
                                            id="company_mobile_2"
                                            name="company_mobile_2"
                                            class="block w-full p-3 border border-gray-900 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            style="width: 100%;"
                                            value="{{ old('company_mobile_2', $setup->company_mobile_2) }}"
                                       required >
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
                    @else
                    <span class="nav-link-text ms-1">KINDLY SET YOUR ACCOUNT SET UP. CLICK ON DASHBOARD TO CONTINUE</span>
                    @endif
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
                                              <!--   <select id="city" name="town_city" class="form-select">
                                                    <option value="">Select a city</option>
                                                </select> -->
                                                <input type="text" value="{{ $shippingAddress->town_city }}" class="form-control" id="town_city" name="town_city" placeholder="Enter City" required>
                                            </div>
                                            <!-- Address -->
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" value="{{ $shippingAddress->address }}" class="form-control" id="address" name="address" placeholder="Enter Address" required>
                                            </div>

                                            <!-- ZIP -->
                                            <div class="mb-3">
                                                <label for="zip" class="form-label">ZIP Code</label>
                                                <input type="text" value="{{ $shippingAddress->zip }}" class="form-control" id="zip" name="zip" placeholder="Enter ZIP Code" required>
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

                                                  <div class="mb-3">
                                                <label for="townCity" class="form-label">Town/City</label>
                                              <!--   <select id="city" name="town_city" class="form-select">
                                                    <option value="">Select a city</option>
                                                </select> -->
                                                <input type="text"  class="form-control" id="town_city" name="town_city" placeholder="Enter City" required>
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
                      <h5>Update Profile</h5>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

let debounceTimer;

function highlightIfEmpty(selector) {
    const input = $(selector);
    if (!input.val().trim()) {
        input.addClass('input-error');
        return true;
    } else {
        input.removeClass('input-error');
        return false;
    }
}

// Make address details readonly
$(document).ready(function () {
    $('#toCity').prop('readonly', true);
    $('#toState').prop('readonly', true);
    $('input[name="country"]').prop('readonly', true);
    $('#postal').prop('readonly', true);
});

$('#toAddress').on('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const address = $('#toAddress').val().trim();
        const firstname = $('#firstname').val().trim();
        const lastname = $('#lastname').val().trim();
        const phone = $('#phone').val().trim();
        const email = $('input[name="email"]').val().trim();

        const hasError = [
            highlightIfEmpty('#firstname'),
            highlightIfEmpty('#lastname'),
            highlightIfEmpty('#phone'),
            highlightIfEmpty('input[name="email"]'),
            highlightIfEmpty('#toAddress')
        ].includes(true);

        if (hasError) {
            $('#addressSuggestions').hide().empty();
            return;
        }

        if (address.length > 4) {
            // Show loading message
            $('#addressSuggestions').empty().show().append(`
                <li class="list-group-item text-muted">Validating address...</li>
            `);

            $.ajax({
                url: '/validate-address',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    name: firstname + ' ' + lastname,
                    email: email,
                    phone: phone,
                    address: address
                },
                success: function (response) {
                    const data = response.data;

                    $('#addressSuggestions').empty().show().append(`
                        <li class="list-group-item suggestion-item" data-address='${JSON.stringify(data)}'>
                            ${data.formatted_address}
                        </li>
                    `);
                },
                error: function (xhr) {
                    let message = "We couldn’t validate your address. Please use Google Maps to confirm it, then copy and paste it here.";

                    $('#addressSuggestions').empty().show().append(`
                        <span class="text-danger fw-bold d-block px-2 py-1">${message}</span>
                    `);
                }
            });
        } else {
            $('#addressSuggestions').hide().empty();
        }
    }, 500);
});

// Fill form fields when suggestion is selected
$(document).on('click', '.suggestion-item', function () {
    const data = $(this).data('address');

    $('#toAddress').val(data.formatted_address);
    $('#toCity').val(data.city);
    $('#toState').val(data.state);
    $('input[name="country"]').val(data.country);
    $('#postal').val(data.address_code);

    $('#addressSuggestions').hide().empty();
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
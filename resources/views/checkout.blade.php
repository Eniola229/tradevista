<?php
use App\Models\Product;
?>
@include('components.header')
<meta name="csrf-token" content="{{ csrf_token() }}">


<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
     @include('components.mobile-nav')
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('components.nav-link')
    <!-- Header Section End -->
    
        <!-- Breadcrumb Begin -->
    <style type="text/css">
        .list-group-item {
    transition: box-shadow 0.3s, transform 0.3s;
    }

    .list-group-item:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .list-group-item.active {
        background-color: #f8f9fa;
        border-color: #007bff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    .list-group-item input[type="radio"]:checked + div {
        color: #007bff;
    }
    </style>
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

    .visually-hidden {
      position: absolute !important;
      width: 1px !important;
      height: 1px !important;
      padding: 0 !important;
      margin: -1px !important;
      overflow: hidden !important;
      clip: rect(0, 0, 0, 0) !important;
      white-space: nowrap !important;
      border: 0 !important;
    }

</style>
   <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Checkout</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

<!-- Checkout Section Begin -->
    <section class="checkout spad">
            @if (session('success'))
                <div class="alert alert-success">
                    <p> {{ session('success') }} </p>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    <p> {{ session('error') }} </p>
                </div>
            @endif
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                                    <div class="alert alert-light border" role="alert" style="font-size: 1.1rem">
                                        <i class="bi bi-info-circle-fill"></i> Your cart will be updated based on your
                                        shipping details, postal code, city, and region. Please
                                        ensure that all information is accurate for the best delivery options.
                                    </div>
                                
                </div>
            </div>
<form id="checkoutForm" action="#" class="checkout__form">
    <div class="row">
        <div class="col-lg-8">
            <h5>Shipping Address</h5>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="checkout__form__input">
                        <p>First Name <span>*</span></p>
                        <input type="text" name="first_name" id="firstname" value="{{ explode(' ', Auth::user()->name)[0] }}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="checkout__form__input">
                        <p>Last Name <span>*</span></p>
                        <input type="text" name="last_name" id="lastname" value="{{ count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name)[1] : null }}" required>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="checkout__form__input">
                        <p>Country <span>*</span></p>
                        <input type="text" name="countryCode" id="country" value="NIGERIA" readonly>
                    </div>
                    <div class="checkout__form__input">
                        <p>Address <span>*</span></p>
                        <input type="text" placeholder="Street Address" value="{{ isset($address) ? $address->address : '' }}" name="shipping_address" id="toAddress" required>
                        <ul id="addressSuggestions" class="list-group position-absolute w-80" style="z-index: 1000;"></ul>
                       
                    </div>
                    <div class="checkout__form__input">
                        <p>Town/City <span>*</span></p>
                        <input type="text" value="{{ isset($address) ? $address->town_city : '' }}" name="citySelect" placeholder="City" id="toCity" name="cityName" required>
                    </div>
                    <div class="checkout__form__input" style="position: relative;">
                        <p>State <span>*</span></p>
                        <input 
                            type="text" 
                            id="shippingState" 
                            name="stateCode" 
                            value="{{ old('stateCode', isset($address) ? $address->state : '') }}" 
                            required 
                            placeholder="Select State"
                            autocomplete="off"
                            onclick="toggleDropdown()"
                           
                        >

                        <!-- Dropdown List -->
                        <div id="stateDropdown" style="display: none; position: absolute; width: 100%; background: white; border: 1px solid #ccc; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); max-height: 200px; overflow-y: auto; z-index: 1000;">
                            <ul style="list-style: none; margin: 0; padding: 0;">
                                @php
                                    $states = [
                                        "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno", "Cross River",
                                        "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "Gombe", "Imo", "Jigawa", "Kaduna", "Kano",
                                        "Katsina", "Kebbi", "Kogi", "Kwara", "Lagos", "Nasarawa", "Niger", "Ogun", "Ondo",
                                        "Osun", "Oyo", "Plateau", "Rivers", "Sokoto", "Taraba", "Yobe", "Zamfara", "FCT"
                                    ];
                                @endphp
                                @foreach($states as $state)
                                    <li onclick="selectState('{{ $state }}')" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #ddd;">
                                        {{ $state }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="checkout__form__input">
                        <p>Postcode/Zip <span>*</span></p>
                        <input type="readonly" placeholder="postal code" value="{{ isset($address) ? $address->zip : '' }}" id="postal" name="zip" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="checkout__form__input">
                        <p>Phone <span>*</span></p>
                        <input type="text" id="phone" name="phone" value="{{ isset($user) ? $user->phone_number : '' }}" required>
                    </div> 
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="checkout__form__input">
                        <p>Email <span>*</span></p>
                        <input type="text" name="email" value="{{ isset($user) ? $user->email : '' }}" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="checkout__form__checkbox">
                        <label for="note">
                            Note about your order, e.g., special note for delivery
                            <input type="checkbox" id="note">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="checkout__form__input">
                        <p>Order notes <span>*</span></p>
                        <input type="text" placeholder="Note about your order, e.g., special note for delivery" name="order_note">
                    </div>
                </div>
            </div>
                         <!------Calculate shipping--->
                            <div id="shipping-method" >
                                <div class="container mt-4">
                                    <h6 class="font-bold" style="font-size: 1.4rem">Shipping method</h6>
                                    
                                    <button id="calculateShippingBtn" class="site-btn mb-3 mt-3">
                                        Calculate Shipping
                                    </button>

                                    <div id="noShippingMethods" class="border rounded p-3 bg-light text-danger" 
                                         style="font-size: 1.2rem;">
                                        Ensure you've validate your shipping address to view available shipping methods.
                                    </div>
                                </div>

                                <div class="text-center" id="loadingIndicator" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading rates...</span>
                                    </div>
                                    <p class="mt-2">Calculating shipping options...</p>
                                </div>

                                <div class="container my-4">
                                    <div id="shippingMethodsContainer"></div>
                                </div>
                            </div>
                    <div class="checkout__form__checkbox">
                        <label for="checked">
                           Use shipping address as billing address.
                            <input type="checkbox" id="checked">
                            <span class="checkmark"></span>
                        </label>
                    </div>
            <div>
                <!-- Shipping Details Form -->
                <div id="shippingDetails" style="display: none;">
                    <div class="col-lg-12">
                        <h5>Billing Details</h5>
                        <div class="checkout__form__input">
                            <p>Country <span>*</span></p>
                            <input type="text" name="countryCode" value="NIGERIA" readonly>
                        </div>
                        <div class="checkout__form__input">
                            <p>Address <span>*</span></p>
                            <input type="text" placeholder="Street Address" name="streetAddress" value="{{ isset($address) ? $address->address : '' }}" >
                            <input type="text" placeholder="Apartment. Suite, etc" name="address">
                        </div>
                        <div class="checkout__form__input">
                            <p>Town/City <span>*</span></p>
                            <input type="text" name="cityName" value="{{ isset($address) ? $address->town_city : '' }}" >
                        </div>
                        <div class="checkout__form__input">
                            <p>State <span>*</span></p>
                            <input type="text" name="stateCode" id="billingState" value="{{ isset($address) ? $address->state : '' }}" >
                        </div>
                        <div class="checkout__form__input">
                            <p>Postcode/Zip <span>*</span></p>
                            <input type="text" name="zipCode" value="{{ isset($address) ? $address->zip : '' }}" >
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="checkout__form__input">
                            <p>Phone <span>*</span></p>
                            <input type="text" name="phone" >
                        </div>
                    </div>
                </div>
            </div>
   
            <ul id="shippingForm" class="mt-4 list-group">
            <!-- Shipping methods will be dynamically added here -->
            </ul>
            <li id="loading" class="d-none mt-3">Loading, please wait...</li>
            <hr>
        </div>

        <div class="col-lg-4">
            <div class="checkout__order">
                <h5>Your order</h5>
                <div class="checkout__order__product">
                    <ul>
                        <li>
                            <span class="top__text">Product</span>
                            <span class="top__text__right">Total</span>
                        </li>
                        @foreach ($cart as $item)
                            <li>{{ $item['product_name'] }} <span>₦ {{ number_format($item['total'], 2) }}</span></li>
                        @endforeach
                    </ul>
                </div>
                <div class="checkout__order__total">
                    <ul>
                        <li>Subtotal <span>₦ {{ number_format($subTotal, 2) }}</span></li>
                        <hr>
                        <li>Shipping Fee <span id="selected-shipping-fee">₦ 0.00</span></li>
                        <li>Total <span  id="totalAmount">₦ {{ number_format($subTotal, 2) }}</span></li>
                    </ul>
                </div>
                <button type="submit" id="placeOrderBtn" class="site-btn">Place Order</button>

            </div>
        </div>
    </div>
</form>

            </div>
        </section>
    <!-- Product Details Section End -->\

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
  window.cartItems = {!! json_encode($cart) !!};
   console.log('Cart Items:', window.cartItems);
document.addEventListener('DOMContentLoaded', function () {
    const calculateShippingBtn = document.getElementById('calculateShippingBtn');
    const toStateInput = document.getElementById('shippingState');
    const toCityInput = document.getElementById('toCity');
    const toAddressInput = document.getElementById('toAddress');
    const postalInput = document.getElementById('postal');
    const shippingMethodSection = document.getElementById('shipping-method');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const shippingMethodsContainer = document.getElementById('shippingMethodsContainer');

    const originalSubTotal = {{ $subTotal }};

    calculateShippingBtn.addEventListener('click', async function (event) {
        event.preventDefault();

        const toState = toStateInput.value.trim();
        const toCity = toCityInput.value.trim();
        const toAddress = toAddressInput.value.trim();
        const postal = postalInput.value.trim();

        if (!toState || !toCity || !toAddress || !postal) {
            showError('Please fill in all fields (state, city, address, and postal code)');
            return;
        }

        startLoading();

        try {
            const response = await fetch('/calculate-shipping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    receiver: {
                        state: toState,
                        city: toCity,
                        address: toAddress,
                        postal: postal
                    },
                    items: getCartItemsWithDimensions()
                })
            });

            const responseData = await response.json();
            console.log('Backend response:', responseData);

            const shippingRates = responseData.shipping_rates;

            if (!shippingRates || Object.keys(shippingRates).length === 0) {
                showNoMethodsAvailable();
                return;
            }

            const firstRateKey = Object.keys(shippingRates)[0];
            const nestedData = shippingRates[firstRateKey];

            if (!nestedData || !nestedData.data || !nestedData.data.couriers || nestedData.data.couriers.length === 0) {
                showNoMethodsAvailable();
                return;
            }

            displayShippingMethods(nestedData.data.couriers);

        } catch (error) {
            showError(error.message);
        } finally {
            stopLoading();
        }
    });

    function startLoading() {
        loadingIndicator.style.display = 'block';
        shippingMethodsContainer.innerHTML = '';
        calculateShippingBtn.disabled = true;
        calculateShippingBtn.innerHTML = 'Calculating...';
    }

    function stopLoading() {
        loadingIndicator.style.display = 'none';
        calculateShippingBtn.disabled = false;
        calculateShippingBtn.innerHTML = 'Calculate Shipping';
    }

    function showError(message) {
        shippingMethodsContainer.innerHTML = `
            <div class="alert alert-danger mt-3">${message}</div>
        `;
    }

    function showNoMethodsAvailable() {
        shippingMethodsContainer.innerHTML = `
            <div class="alert alert-warning mt-3">No shipping methods available for this address</div>
        `;
    }

    function displayShippingMethods(couriers) {
        let html = '<h5 class="mt-3">Available Shipping Methods:</h5>';

        couriers.forEach((courier, index) => {
            const discount = courier.discount || { discounted: '0', symbol: '' };

            html += `
            <div class="card mb-3 shipping-option" style="border: 2px solid #ccc; border-radius: 12px; transition: border-color 0.3s; cursor: pointer;">
              <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div class="form-check" style="flex-grow: 1;">
                  <input class="form-check-input shipping-radio" type="radio" name="shippingMethod"
                    value="${courier.courier_id}" data-total="${courier.total}" id="ship-${index}" ${index === 0 ? 'checked' : ''}>
                  <label class="form-check-label" for="ship-${index}" style="cursor: pointer;">
                    <strong>${courier.courier_name}</strong><br>
                    <span>₦${Number(courier.total).toLocaleString()}</span><br>
                    <small>Delivery ETA: ${courier.delivery_eta}</small><br>
                    <small>Pickup ETA: ${courier.pickup_eta}</small><br>
                    <small style="color: red;">Discount: ${discount.discounted}${discount.symbol}</small>
                  </label>
                </div>
                <div class="text-center mt-3 mt-md-0">
                  <img src="${courier.courier_image}" alt="${courier.courier_name}" width="80" style="border-radius: 6px;">
                </div>
              </div>
            </div>
            `;
        });

        shippingMethodsContainer.innerHTML = html;

        document.querySelectorAll('.shipping-radio').forEach(input => {
            input.addEventListener('change', updateTotalWithShipping);
        });

        updateTotalWithShipping();
    }

    function updateTotalWithShipping() {
        const selected = document.querySelector('.shipping-radio:checked');
        const shippingFee = selected ? parseFloat(selected.dataset.total) : 0;

        const shippingDisplay = document.getElementById('selected-shipping-fee');
        if (shippingDisplay) {
            shippingDisplay.textContent = `₦${shippingFee.toLocaleString()}`;
        }

        const totalDisplay = document.getElementById('totalAmount');
        if (totalDisplay) {
            const newTotal = originalSubTotal + shippingFee;
            totalDisplay.textContent = newTotal.toLocaleString();
        }
    }

    function getCartItemsWithDimensions() {
        // Convert object to array of product objects
        const items = window.cartItems && typeof window.cartItems === 'object'
            ? Object.values(window.cartItems)
            : [];

        console.log("Raw cart items array:", items);

        const result = items.map(item => {
            const name = item.product_name;
            const amount = item.product_price;

            if (!name || amount === undefined || amount === null) {
                console.warn("Invalid cart item:", item);
                return null;
            }

            return {
                name,
                description: item.description || 'No description',
                unit_weight: String(item.weight || '1.0'), // use 'weight' from your data
                unit_amount: String(amount),
                quantity: parseInt(item.quantity || 1, 10),
                dimension: {
                    length: 10,  // don't have length/width/height, so using default 10
                    width: 10,
                    height: 10
                }
            };
        }).filter(item => item !== null);

        console.log("Validated Cart Items:", result);
        return result;
    }
});

document.querySelectorAll('.shipping-option').forEach(option => {
  option.addEventListener('click', function (e) {
    // Prevent double triggering if clicking the radio directly
    const radio = this.querySelector('.shipping-radio');

    // Set this radio to checked
    radio.checked = true;

    // Remove green border from all
    document.querySelectorAll('.shipping-option').forEach(opt => {
      opt.style.borderColor = '#ccc';
      opt.querySelector('.form-check-input').style.accentColor = '';
    });

    // Add green border to selected
    this.style.borderColor = 'green';
    radio.style.accentColor = 'green';
  });
});

// On page load, highlight already checked one
document.querySelectorAll('.shipping-option').forEach(option => {
  const radio = option.querySelector('.shipping-radio');
  if (radio.checked) {
    option.style.borderColor = 'green';
    radio.style.accentColor = 'green';
  }
});
</script>

<script>
 window.cartItems = {!! json_encode($cart) !!};

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
    $('#country').val(data.country);
    $('#postal').val(data.address_code);

    $('#addressSuggestions').hide().empty();
});
</script>


<script>
$(document).ready(function () {
    $('#placeOrderBtn').click(function (event) {
        event.preventDefault(); 

        let button = $(this);
        let originalText = button.html();

        // Validate required fields
        let missingFields = [];
        $('input[required]').each(function () {
            if (!$(this).val().trim()) {
                let fieldLabel = $(this).attr('placeholder') || $(this).attr('name'); // Get field label or name
                missingFields.push(fieldLabel);
                $(this).addClass('border-danger'); // Highlight missing fields
            } else {
                $(this).removeClass('border-danger'); // Remove highlight if filled
            }
        });

        if (missingFields.length > 0) {
            let errorMessage = "Please fill in the following fields:\n" + missingFields.join(', ');
            Swal.fire('Error', errorMessage, 'error');
            return;
        }

        // Validate Shipping Fee
        let shippingFeeText = $('#selected-shipping-fee').text().trim();
        if (shippingFeeText === '₦ 0.00' || shippingFeeText === '' || shippingFeeText === '₦') {
            Swal.fire('Error', 'Please click on "Calculate Rates" before proceeding.', 'error');
            return;
        }

        // Show loading state
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        // Read total amount and convert to kobo
        let totalText = $('#totalAmount').text().replace('₦', '').replace(/,/g, '');
        let totalAmount = parseFloat(totalText);

        if (totalAmount <= 0) {
            Swal.fire('Error', 'Total amount is invalid!', 'error');
            button.prop('disabled', false).html(originalText);
            return;
        }

        let amountInKobo = totalAmount * 100;

        var handler = PaystackPop.setup({
            key: "{{ config('services.paystack.public_key') }}",
            email: "{{ Auth::user()->email }}",
            amount: amountInKobo,
            currency: "NGN",
            ref: '' + Math.floor((Math.random() * 1000000000) + 1),
            callback: function (response) {
                verifyPayment(response.reference, button, originalText);
            },
            onClose: function () {
                Swal.fire('Cancelled', 'You closed the payment window.', 'error');
                button.prop('disabled', false).html(originalText);
            }
        });

        handler.openIframe();
    });

    function verifyPayment(reference, button, originalText) {
        let formData = {
            first_name: $('input[name="first_name"]').val(),
            last_name: $('input[name="last_name"]').val(),
            phone: $('input[name="phone"]').val(),
            email: $('input[name="email"]').val(),
            stateCode: $('input[name="stateCode"]').val(),
            cityName: $('input[name="cityName"]').val(),
            zip: $('input[name="zip"]').val(),
            shipping_address: $('input[name="shipping_address"]').val(),
            subtotal: "{{ $subTotal }}",
            shipping_fee: parseFloat($('#selected-shipping-fee').text().replace('₦', '').replace(/,/g, '')),
            reference: reference,
            order_note: $('input[name="order_note"]').val(),
            _token: "{{ csrf_token() }}"
        };

        $('#loading').removeClass('d-none'); // Show loading

        $.ajax({
            url: "{{ route('verify.payment') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                $('#loading').addClass('d-none');
                button.prop('disabled', false).html(originalText);

                if (response.success) {
                    Swal.fire('Success', 'Order placed successfully!', 'success')
                        .then(() => window.location.href = "/user/orders");
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                $('#loading').addClass('d-none');
                button.prop('disabled', false).html(originalText);
                Swal.fire('Error', xhr.responseJSON?.message || "Something went wrong!", 'error');
            }
        });
    }
});
</script>

<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("stateDropdown");
        dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
    }

    function selectState(state) {
        document.getElementById("shippingState").value = state;
        document.getElementById("stateDropdown").style.display = "none";
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        var input = document.getElementById("shippingState");
        var dropdown = document.getElementById("stateDropdown");

        if (!input.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });

    // $(document).ready(function () {
    //     $('#calculateRates').click(function () {
    //         let shippingState = $('#shippingState').val().trim();
            
    //         if (shippingState === '') {
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Error',
    //                 text: 'Please enter a state before calculating rates!',
    //             });
    //             return;
    //         }

    //         $('#loading').removeClass('d-none'); // Show loading message

    //         $.ajax({
    //             url: "{{ route('calculate.shipping') }}",
    //             type: "POST",
    //             data: {
    //                 stateCode: shippingState,
    //                 _token: "{{ csrf_token() }}"
    //             },
    //             success: function (response) {
    //                 $('#loading').addClass('d-none'); // Hide loading message

    //                 if (response.success) {
    //                     let shippingPrice = response.price;
    //                     let subTotal = parseFloat("{{ $subTotal }}".replace(/,/g, '')); // Convert subtotal from Blade

    //                     let totalAmount = subTotal + shippingPrice; // Calculate new total

    //                     // Update shipping price in UI
    //                     $('#rate').text(`₦ ${shippingPrice.toLocaleString()}`);
    //                     $('#totalAmount').text(`₦ ${totalAmount.toLocaleString()}`);

    //                     // Add shipping price inside #shippingForm
    //                     let priceList = `<li class="list-group-item">Shipping Price: ₦${shippingPrice.toLocaleString()}</li>`;
    //                     $('#shippingForm').html(priceList); // Replace existing content
    //                 } else {
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'Error',
    //                         text: response.message,
    //                     });
    //                 }
    //             },
    //             error: function (xhr) {
    //                 $('#loading').addClass('d-none');
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Error',
    //                     text: xhr.responseJSON?.message || "Something went wrong!",
    //                 });
    //             }
    //         });
    //     });
    // });
</script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("checked");
    const billingDetails = document.getElementById("shippingDetails");

    // Auto-check the checkbox when the page loads
    checkbox.checked = true;

    // Initially hide the billing form because the checkbox is checked
    billingDetails.style.display = "none";

    // Add an event listener to toggle the billing form visibility
    checkbox.addEventListener("change", function () {
      if (checkbox.checked) {
        // Hide the billing form when the checkbox is checked
        billingDetails.style.display = "none";
      } else {
        // Show the billing form when the checkbox is unchecked
        billingDetails.style.display = "block";
      }
    });
  });
</script>

@include('components.footer')
</html>

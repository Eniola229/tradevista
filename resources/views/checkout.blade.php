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
                        <input type="text" name="first_name" value="{{ explode(' ', Auth::user()->name)[0] }}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="checkout__form__input">
                        <p>Last Name <span>*</span></p>
                        <input type="text" name="last_name" value="{{ explode(' ', Auth::user()->name)[1] }}">
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="checkout__form__input">
                        <p>Country <span>*</span></p>
                        <input type="text" name="countryCode" value="NIGERIA" readonly>
                    </div>
                    <div class="checkout__form__input">
                        <p>Address <span>*</span></p>
                        <input type="text" placeholder="Street Address" value="{{ isset($address) ? $address->address : '' }}" name="shipping_address" required>
                        <input type="text" placeholder="Apartment. suite, etc (Optional)" name="address" >
                    </div>
                    <div class="checkout__form__input">
                        <p>Town/City <span>*</span></p>
                        <input type="text" value="{{ isset($address) ? $address->town_city : '' }}" name="cityName" required>
                    </div>
                    <div class="checkout__form__input">
                        <p>State <span>*</span></p>
                        <input type="text" value="{{ isset($address) ? $address->state : '' }}" id="shippingState" name="stateCode" required>
                    </div>
                    <div class="checkout__form__input">
                        <p>Postcode/Zip <span>*</span></p>
                        <input type="text" value="{{ isset($address) ? $address->zip : '' }}" name="zip" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="checkout__form__input">
                        <p>Phone <span>*</span></p>
                        <input type="text" name="phone" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="checkout__form__input">
                        <p>Email <span>*</span></p>
                        <input type="text" name="email" value="{{ Auth::user()->email }}" required>
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
            <button type="button" id="calculateRates" class="site-btn">Calculate Rates</button>
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
                        <li>Shipping Fee <span id="rate">₦ 0.00</span></li>
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
$(document).ready(function () {
    $('#placeOrderBtn').click(function (event) {
        event.preventDefault(); // Prevent form submission from reloading the page

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
        let shippingFeeText = $('#rate').text().trim();
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
            shipping_fee: parseFloat($('#rate').text().replace('₦', '').replace(/,/g, '')),
            reference: reference,
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
    $(document).ready(function () {
        $('#calculateRates').click(function () {
            let shippingState = $('#shippingState').val().trim();
            
            if (shippingState === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter a state before calculating rates!',
                });
                return;
            }

            $('#loading').removeClass('d-none'); // Show loading message

            $.ajax({
                url: "{{ route('calculate.shipping') }}",
                type: "POST",
                data: {
                    stateCode: shippingState,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#loading').addClass('d-none'); // Hide loading message

                    if (response.success) {
                        let shippingPrice = response.price;
                        let subTotal = parseFloat("{{ $subTotal }}".replace(/,/g, '')); // Convert subtotal from Blade

                        let totalAmount = subTotal + shippingPrice; // Calculate new total

                        // Update shipping price in UI
                        $('#rate').text(`₦ ${shippingPrice.toLocaleString()}`);
                        $('#totalAmount').text(`₦ ${totalAmount.toLocaleString()}`);

                        // Add shipping price inside #shippingForm
                        let priceList = `<li class="list-group-item">Shipping Price: ₦${shippingPrice.toLocaleString()}</li>`;
                        $('#shippingForm').html(priceList); // Replace existing content
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function (xhr) {
                    $('#loading').addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || "Something went wrong!",
                    });
                }
            });
        });
    });
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

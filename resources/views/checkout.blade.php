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
                        <input type="text" placeholder="Street Address" value="{{ isset($address) ? $address->address : '' }}" required>
                        <input type="text" placeholder="Apartment. suite, etc (Optional)" name="address" >
                    </div>
                    <div class="checkout__form__input">
                        <p>Town/City <span>*</span></p>
                        <input type="text" value="{{ isset($address) ? $address->town_city : '' }}" name="cityName" required>
                    </div>
                    <div class="checkout__form__input">
                        <p>State <span>*</span></p>
                        <input type="text" value="{{ isset($address) ? $address->state : '' }}" name="stateCode" required>
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
                        <input type="text" name="email" value="{{ Auth::user()->email }}">
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
                            <input type="text" placeholder="Street Address" name="streetAddress" value="{{ isset($address) ? $address->address : '' }}" required>
                            <input type="text" placeholder="Apartment. Suite, etc" name="address">
                        </div>
                        <div class="checkout__form__input">
                            <p>Town/City <span>*</span></p>
                            <input type="text" name="cityName" value="{{ isset($address) ? $address->town_city : '' }}" required>
                        </div>
                        <div class="checkout__form__input">
                            <p>State <span>*</span></p>
                            <input type="text" name="stateCode" value="{{ isset($address) ? $address->state : '' }}" required>
                        </div>
                        <div class="checkout__form__input">
                            <p>Postcode/Zip <span>*</span></p>
                            <input type="text" name="zipCode" value="{{ isset($address) ? $address->zip : '' }}" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="checkout__form__input">
                            <p>Phone <span>*</span></p>
                            <input type="text" name="phone" required>
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
                <button type="submit" class="site-btn">Place Order</button>
            </div>
        </div>
    </div>
</form>

            </div>
        </section>
    <!-- Product Details Section End -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const exchangeRateToNGN = 760; // Replace with the actual exchange rate

    document.getElementById('calculateRates').addEventListener('click', async () => {
        const loading = document.getElementById('loading');
        const shippingForm = document.getElementById('shippingForm');
        const rateElement = document.getElementById('rate'); // Shipping fee element
        const totalElement = document.getElementById('totalAmount'); // Total element to update

        // Show the loading indicator
        loading.classList.remove('d-none');
        shippingForm.innerHTML = ''; // Clear previous results

        try {
            // Fetch the rates from your FedEx route
            const response = await fetch('/checkout/get-shipping-fee');
            const data = await response.json();

            // Hide the loading indicator
            loading.classList.add('d-none');

            // Extract rate details
            const rateDetails = data.output.rateReplyDetails;

            let selectedShippingFee = 0; // Variable to store the selected shipping fee in NGN

            // Loop through rates and display them
            rateDetails.forEach((detail) => {
                const serviceName = detail.serviceName;
                const deliveryDays = detail.transitTime || 'N/A';
                const formattedDeliveryDays = 
                    typeof deliveryDays === 'number' 
                        ? new Intl.NumberFormat().format(deliveryDays) 
                        : deliveryDays;
                
                const priceUSD = detail.ratedShipmentDetails[0]?.totalNetFedExCharge || 0;
                const priceNGN = priceUSD * exchangeRateToNGN;

                // Create a list item with radio button
                const listItem = document.createElement('li');
                listItem.classList.add(
                    'list-group-item',
                    'd-flex',
                    'align-items-center',
                    'p-3',
                    'rounded',
                    'shadow-sm',
                    'hover-shadow',
                    'cursor-pointer'
                );

                listItem.innerHTML = ` 
                    <div class="d-flex align-items-center w-100">
                        <!-- Left: Radio Button -->
                        <input type="radio" name="shippingOption" value="${serviceName}" class="form-check-input me-3" data-price="${priceNGN}">

                        <!-- Middle: Service Name and Delivery Days -->
                        <div class="flex-grow-1">
                            <div class="fw-bold">${serviceName}</div>
                            <small class="text-muted">${formattedDeliveryDays} delivery</small>
                        </div>

                        <!-- Right: Price -->
                        <div class="text-success fw-bold text-end ms-auto">
                            ₦ ${new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN', minimumFractionDigits: 2 }).format(priceNGN).replace('₦','')}
                        </div>
                    </div>
                `;

                // Add click event to select the item
                listItem.addEventListener('click', () => {
                    shippingForm.querySelectorAll('.list-group-item').forEach((item) => item.classList.remove('active'));
                    listItem.classList.add('active');
                    listItem.querySelector('input').checked = true;

                    // Update the shipping fee when an option is selected
                    selectedShippingFee = parseFloat(listItem.querySelector('input').dataset.price);

                    // Update the shipping fee and total dynamically
                    rateElement.textContent = `₦ ${new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN', minimumFractionDigits: 2 }).format(selectedShippingFee).replace('₦','')}`;
                    updateTotalPrice(selectedShippingFee);
                });

                // Append to the form
                shippingForm.appendChild(listItem);
            });

        } catch (error) {
            // Hide the loading indicator and show an error
            loading.classList.add('d-none');
            shippingForm.innerHTML = ` 
                <li class="list-group-item text-danger">Failed to fetch shipping rates. Please try again.</li>
            `;
            console.error(error);
        }
    });

function updateTotalPrice(shippingFee) {
    // Retrieve the subtotal from the PHP side, remove commas, and ensure it's a valid number
    const subTotal = parseFloat(
        "{{ number_format($subTotal, 2) }}"
            .replace(/,/g, '') // Remove all commas globally
            .replace('₦', '') // Remove the currency symbol
    );

    console.log(subTotal);

    // Ensure subTotal is a valid number
    if (isNaN(subTotal)) {
        console.error("Invalid subtotal value");
        return;
    }

    // Calculate the total by adding the shipping fee to the subtotal
    const total = subTotal + shippingFee;

    // Update the total value on the page using the id "totalAmount"
    const totalElement = document.getElementById('totalAmount');
    if (totalElement) {
        // Format the total as Nigerian currency
        totalElement.textContent = new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN',
            minimumFractionDigits: 2
        }).format(total);
    }
}

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

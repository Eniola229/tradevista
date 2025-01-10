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
    
    <!-- Shop Section Begin -->
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
                        <h5>Billing detail</h5>
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
                                    <input type="text" name="countryCode", value="NIGERIA" readonly>
                                </div>
                                <div class="checkout__form__input">
                                    <p>Address <span>*</span></p>
                                    <input type="text" placeholder="Street Address">
                                    <input type="text" placeholder="Apartment. suite, etc" name="address" required>
                                </div>
                                <div class="checkout__form__input">
                                    <p>Town/City <span>*</span></p>
                                    <input type="text" value="{{ $adrress->town_city }}"  name="cityName" required>
                                </div>
                                <div class="checkout__form__input">
                                    <p>State <span>*</span></p>
                                    <input type="text" value="{{ $adrress->state }}"  name="stateCode" required>
                                </div>
                                <div class="checkout__form__input">
                                    <p>Postcode/Zip <span>*</span></p>
                                    <input type="text" value="{{ $adrress->zip }}" name="zip" required>
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
                           <!--      <div class="checkout__form__checkbox">
                                    <label for="acc">
                                        Create an acount?
                                        <input type="checkbox" id="acc">
                                        <span class="checkmark"></span>
                                    </label>
                                    <p>Create am acount by entering the information below. If you are a returing
                                        customer login at the <br />top of the page</p>
                                    </div> -->
                                 <!--    <div class="checkout__form__input">
                                        <p>Account Password <span>*</span></p>
                                        <input type="text">
                                    </div> -->
                                    <div class="checkout__form__checkbox">
                                        <label for="note">
                                            Note about your order, e.g, special note for delivery
                                            <input type="checkbox" id="note">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Oder notes <span>*</span></p>
                                        <input type="text"
                                        placeholder="Note about your order, e.g, special noe for delivery" name="order_note">
                                    </div>
                                </div>
                            </div>
                         <div>
                        <!-- Checkbox for using billing as shipping details -->
                         <div class="checkout__form__checkbox">
                            <label>
                                  Use Billing as Shipping Details
                                <input type="checkbox" id="useBillingAsShipping" onclick="toggleShippingDetails()"> 
                                 <span class="checkmark"></span>
                              
                            </label>
                            </div>

                        <!-- Checkbox for manual shipping details -->
                        <div class="checkout__form__checkbox">
                            <label>
                                 Enter Shipping Details
                                <input type="checkbox" id="manualShipping" onclick="toggleShippingDetails()">
                                 <span class="checkmark"></span>
                            </label>
                        </div>

                        <!-- Shipping details form -->
                        <div id="shippingDetails" style="display: none;">
                            <div class="col-lg-12">
                                <h5>Shipping Details</h5>
                                <div class="col-lg-12">
                                    <div class="checkout__form__input">
                                        <p>Country <span>*</span></p>
                                        <input type="text" name="countryCode" value="NIGERIA" readonly>
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Address <span>*</span></p>
                                        <input type="text" placeholder="Street Address" name="streetAddress">
                                        <input type="text" placeholder="Apartment. Suite, etc" name="address" required>
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Town/City <span>*</span></p>
                                        <input type="text" name="cityName" value="{{ $adrress->town_city }}" required>
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>State <span>*</span></p>
                                        <input type="text" name="stateCode" value="{{ $adrress->state }}" required>
                                    </div>
                                    <div class="checkout__form__input">
                                        <p>Postcode/Zip <span>*</span></p>
                                        <input type="text" name="zipCode" value="{{ $adrress->zip }}" required>
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
                    </div>

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
                                        <li>Shipping Fee <span id="shippingFee">₦ 0.00</span></li>
                                      
                                        <li>Total <span>₦ {{ number_format($subTotal, 2) }}</span></li>
                                    </ul>
                                </div>
                                 @if (!auth()->check())
                                <div class="checkout__order__widget">
                                    <label for="o-acc">
                                        Create an acount?
                                        <input type="checkbox" id="o-acc">
                                        <span class="checkmark"></span>
                                    </label>
                                    <p>Create am acount by entering the information below. If you are a returing customer
                                    login at the top of the page.</p>
                                    @endif
                                    
                                    <div class="checkout__form__checkbox">
                                    <label>
                                      Pay with Paystack
                                        <input type="checkbox" id="manualShipping">
                                         <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="checkout__form__checkbox">
                                    <label>
                                      Pay with Interswitch
                                        <input type="checkbox" id="manualShipping">
                                         <span class="checkmark"></span>
                                    </label>
                                </div>
                                        <span class="checkmark"></span>
                                         <button type="submit" class="site-btn">Place oder</button>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- Checkout Section End -->
 <button onclick="calculateShippingFee()">Calculate Shipping Fee</button>
    <!-- Product Details Section End -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const origin = {
    city: 'New York',
    stateOrProvinceCode: 'NY',
    postalCode: '10001',
    countryCode: 'US',
};

const destination = {
    city: 'Los Angeles',
    stateOrProvinceCode: 'CA',
    postalCode: '90001',
    countryCode: 'US',
};

const packageDetails = {
    weight: 5, // in kg
    dimensions: {
        length: 30, // in cm
        width: 20, // in cm
        height: 10, // in cm
    },
};

// Get the CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function calculateShippingFee() {
    try {
        const queryParams = new URLSearchParams({
            'origin[city]': origin.city,
            'origin[stateOrProvinceCode]': origin.stateOrProvinceCode,
            'origin[postalCode]': origin.postalCode,
            'origin[countryCode]': origin.countryCode,
            'destination[city]': destination.city,
            'destination[stateOrProvinceCode]': destination.stateOrProvinceCode,
            'destination[postalCode]': destination.postalCode,
            'destination[countryCode]': destination.countryCode,
            'weight': packageDetails.weight,
            'dimensions[length]': packageDetails.dimensions.length,
            'dimensions[width]': packageDetails.dimensions.width,
            'dimensions[height]': packageDetails.dimensions.height,
        });

        const response = await fetch(`/calculate-shipping-fee?${queryParams.toString()}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        const data = await response.json();
        if (data.shipping_fee) {
            console.log('Shipping Fee:', data.shipping_fee);
            alert(`Shipping Fee: $${data.shipping_fee}`);
        } else {
            console.log('Error:', data.error);
        }
    } catch (error) {
        console.error('Error calculating shipping fee:', error);
    }
}

calculateShippingFee();
</script>




<script type="text/javascript">
    function toggleShippingDetails() {
    const useBillingAsShipping = document.getElementById('useBillingAsShipping');
    const manualShipping = document.getElementById('manualShipping');
    const shippingDetails = document.getElementById('shippingDetails');

    // If "Use Billing as Shipping Details" is checked, hide the manual shipping form
    if (useBillingAsShipping.checked) {
        shippingDetails.style.display = 'none';
    }

    // If "Enter Shipping Details" is checked, show the manual shipping form
    else if (manualShipping.checked) {
        shippingDetails.style.display = 'block';
    } else {
        shippingDetails.style.display = 'none';
    }
}

</script>
@include('components.footer')
</html>

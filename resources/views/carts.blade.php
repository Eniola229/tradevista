<?php
use App\Models\Product;
?>
@include('components.header')
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
                        <span>Shopping cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Cart Section Begin -->
     @if (count($cart) > 0)
    <section class="shop-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shop__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $index => $item)
                                <tr>
                                    <td class="cart__product__item">
                                        <img src="{{ $item['image_url'] }}" width="70" alt="">
                                        <div class="cart__product__item__title">
                                            <h6>{{ $item['product_name'] }}</h6>
                                        </div>
                                    </td>
                                    <td class="cart__price">₦ <?php 
                                        $discounted_price = Product::getDiscountedPrice($item['id']); 
                                    ?>
                                    @if($discounted_price > 0)
                                       {{ number_format($discounted_price, 2) }}
                                    @else
                                    {{ number_format($item['product_price'], 2) }}
                                    @endif
                                    </td>
                                    <td class="cart__quantity">
                                        <div class="pro-qty" data-product-id="{{ $item['id'] }}" data-index="{{ $index }}">
                                            <input type="text" value="{{ $item['quantity'] }}">
                                        </div>
                                    </td>
                                    <td class="cart__total">₦ {{ number_format($item['total']) }}</td>
                                    
                                    <td class="cart__close remove-item" data-product-id="{{ $item['id'] }}"><span class="icon_close"></span></td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="cart__btn">
                        <a href="{{ url('products') }}">Continue Shopping</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="cart__btn update__btn">
                        <a href="#" onclick="window.location.reload();"><span class="icon_loading"></span> Update cart</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="discount__content">
                        <h6>Discount codes</h6>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">Apply</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 offset-lg-2">
                    <div class="cart__total__procced">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Subtotal <span>₦ {{ number_format($totalAmount) }}</span></li>
                            <li>Total <span>₦ {{ number_format($totalAmount) }}</span></li>
                        </ul>
                        <a href="{{ url('/checkout') }}" class="site-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
        @else
        <div class="container text-center">
            <h4>Your cart is empty.</h4>
            <div class="cart__btn update__btn" style="justify-content: center; align-items: center; margin: auto;">
             <a href="{{ url('products') }}" ><span class="icon_loading"></span> Products</a>
         </div>
    @endif
    <!-- Product Details Section End -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
$(document).on('click', '.remove-item', function (e) {
    e.preventDefault(); // Prevent default button behavior

    var productId = $(this).data('product-id'); // Get product ID from data attribute
    console.log("Clicked button for product ID:", productId); // Debug log

    // Confirm the action
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to remove this item from the cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('cart.remove') }}", // Backend route for removing item
                type: "GET", // HTTP method
                data: { product_id: productId }, // Data to send to the server
                beforeSend: function () {
                    console.log("Sending request to remove product ID:", productId); // Log before sending
                },
                success: function (response) {
                    console.log("Server response:", response); // Log the server response

                    // Show success alert
                    Swal.fire({
                        title: 'Removed!',
                        text: 'Item removed from the cart successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload the page to update the cart
                        window.location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error details:", {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    }); // Log detailed error

                    // Show error alert
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was a problem removing the item from the cart.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});

</script>

<script type="text/javascript">
$(document).ready(function () {
    // Function to display toast messages
    function showToast(message, type) {
        Swal.fire({
            text: message,
            icon: type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }

    // Increment quantity
    $(".plus").on("click", function () { 
        var productId = $(this).data("product-id"); // Get product ID from data attribute
        console.log("Product ID: ", productId);
        $.ajax({
            url: "{{ route('update.increase.cart') }}", // Backend route for increasing quantity
            type: "GET",
            data: { product_id: productId },
            success: function (response) {
                // Update UI if required
                showToast('Quantity increased successfully!', 'success');
                // Optional: reload page to reflect changes
                // window.location.reload();
            },
            error: function (xhr) {
                showToast('Error updating quantity!', 'error');
            }
        });
    });

    // Decrement quantity
    $(".minus").on("click", function () {
        var productId = $(this).data("product-id"); // Get product ID from data attribute
        $.ajax({
            url: "{{ route('update.decrease.cart') }}", // Backend route for decreasing quantity
            type: "GET",
            data: { product_id: productId },
            success: function (response) {
                // Update UI if required
                showToast('Quantity decreased successfully!', 'success');
                // Optional: reload page to reflect changes
                // window.location.reload();
            },
            error: function (xhr) {
                showToast('Error updating quantity!', 'error');
            }
        });
    });
});

</script>
@include('components.footer')
</html>

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
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                        <span>Wishlist</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Cart Section Begin -->
      @if (count($wishlist) > 0)
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
                                </tr>
                            </thead>
                            <tbody>
                                   @foreach($wishlist as $index => $item)
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
                                    
                                    <td class="cart__close remove-item-wishlist" data-product-id="{{ $item['id'] }}"><span class="icon_close"></span></td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
        @else
        <div class="container text-center">
            <h4>Your Wishlist is empty.</h4>
            <div class="cart__btn update__btn" style="justify-content: center; align-items: center; margin: auto;">
             <a href="{{ url('products') }}" ><span class="icon_loading"></span> Products</a>
         </div>
    @endif
    <!-- Product Details Section End -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@include('components.footer')
</html>

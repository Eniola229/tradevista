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
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="#">{{ $category->name }} </a>
                        <span>{{ $product->product_name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__left product__thumb nice-scroll">
                            <a class="pt active" href="#product-1">
                                <img src="{{ $product->image_url }}" alt="">
                            </a>
                               @if($images && $images->isNotEmpty())
                                  @foreach($images as $key => $image)
                                      <a class="pt" href="#product-{{ $key + 2 }}">
                                          <img src="{{ $image->image_url }}" alt="Product Image {{ $key + 2 }}">
                                      </a>
                                  @endforeach
                              @endif
                        </div>
                        <div class="product__details__slider__content">
                            <div class="product__details__pic__slider owl-carousel">
                                <img data-hash="product-1" class="product__big__img" src="{{ $product->image_url }}" alt="">
                                 @if($images && $images->isNotEmpty())
                                    @foreach($images as $key => $image)
                                        <img data-hash="product-{{ $key + 2 }}" class="product__big__img" src="{{ $image->image_url }}" alt="Product Image {{ $key + 2 }}">
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product__details__text">
                        <h3>{{ $product->product_name }} <h6>Brand: {{ $setup->company_name }}</h6></h3><br>
                       <span>You can contact the seller to place an order, <span style="color: red;">(though we recommend placing all orders online for security purposes)</span></span>
                        <h6>{{ $setup->company_mobile_1 }} | {{ $setup->company_mobile_1 }}</h6>
                        <div class="rating">
                          @if($reviews->isNotEmpty())
                            @foreach($reviews as $review)
                                <div class="review">
                                    <!-- <p>{{ $review->user->name ?? 'Anonymous' }}</p> -->
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="fa fa-star"></i> <!-- Filled star -->
                                            @else
                                                <i class="fa fa-star-o"></i> <!-- Empty star -->
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No reviews yet.</p>
                        @endif
                            <span>( {{ $reviewCount }} reviews )</span>
                        </div>
                     <div class="product__details__price">
                                ₦ {{ number_format($product->product_discount, 2) }} 
                                <span>₦ {{ number_format($product->product_price, 2) }}</span>
                            </div>

                        <form id="cart-form" action="{{ route('add-to-shopping-cart') }}" method="GET">
                                  <input type="hidden" value="{{ $product->id }}" name="product_id">
                        <div class="product__details__button">
                            <div class="quantity">
                                <span>Quantity:</span>
                                <div class="pro-qty">
                                    <input type="text" value="1" name="quantity" id="number_qaun" class="number_qaun" >
                                </div>
                            </div>         
                                <a href="#" class="cart-btn product-btn"  id="addCart" data-product-id="{{ $product->id }}"><span class="icon_bag_alt"></span> Add to cart</a>
                            </form>
                                <ul>
                                <form class="wishlistForm" action="{{ route('addWishlist') }}" method="GET">
                                @csrf
                                 <input type="hidden" name="product_id" value="{{ $product->id }}">
                              
                                <li><a href="#"  data-product-id="{{ $product->id }}" class=" addWishlistBtn"><span class="icon_heart_alt"></span></a></li>
                                
                            </form>
                                <!-- <li><a href="#"><span class="icon_adjust-horiz"></span></a></li> -->
                            </ul>
                        </div>
                        <div class="product__details__widget">
                            <ul>
                                <li>
                                    <span>Availability:</span>
                                    <div class="stock__checkbox">
                                        <label for="stockin">
                                          @if($product->stock == 0)
                                              <p>Out of stock</p>
                                          @else
                                              <p>In stock: {{ $product->stock }} remaining</p>
                                          @endif
                                            <input type="checkbox" id="stockin">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Category:</span>
                                    <div class="color__checkbox">
                                       <label for="xs-btn" class="active">
                                            <input type="radio" id="xs-btn">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Product size:</span>
                                    <div class="size__btn">
                                        <label for="xs-btn" class="active">
                                            <input type="radio" id="xs-btn">
                                            @if($attribute)
                                            {{ $attribute->size }}
                                            @else
                                            <p style="color: red;">NOT AVALIABLE</p>
                                            @endif
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <span>Shipping fee:</span>
                                    <p>₦ {{ $product->shipping_fee }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Meta Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Reviews ( {{ $reviewCount }} )</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <h6>Description</h6>
                                <p>{{ $product->product_description }}</p>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <h6>Meta Description</h6>
                                <p>{{ $product->meta_description }}
                                   .</p>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <h6>Reviews ( {{ $reviewCount }} )</h6>
                                @foreach($reviews as $review)
                                <p>
                                  {{ $review->review }}
                                 </p>
                                  
                                  
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="related__title">
                        <h5>RELATED PRODUCTS</h5>
                    </div>
                </div>
             @foreach($relatedProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mix {{ \Str::slug($product->category->name) }} view-product-btn" id="viewProductBtn" data-product-id="{{ $product->id }}">
                <div class="product__item">
                    <div class="product__item__pic set-bg" data-setbg="{{ $product->image_url }}">
                        @if(\Carbon\Carbon::parse($product->created_at)->gt(\Carbon\Carbon::now()->subDay()))
                            <div class="label new">New</div>
                        @endif
                        @if($product->stock <= 0)
                            <div class="label stockout">Out of stock</div>
                        @endif

                        <ul class="product__hover" style="display: flex; justify-content: center; margin: auto;">
                            <li><a href="{{ $product->image_url }}" class="image-popup"><span class="arrow_expand"></span></a></li>
                              <form class="wishlistForm" action="{{ route('addWishlist') }}" method="GET">
                                @csrf
                                 <input type="hidden" name="product_id" value="{{ $product->id }}">
                              
                                <li><a href="#"  data-product-id="{{ $product->id }}" class=" addWishlistBtn"><span class="icon_heart_alt"></span></a></li>
                                
                            </form>
                             <form id="cart-form" action="{{ route('add-to-shopping-cart') }}" method="GET">
                                  <input type="hidden" value="{{ $product->id }}" name="product_id">
                                     <input type="hidden" value="1" name="quantity" id="number_qaun" class="number_qaun" >
                            <li class="product-btn"  id="addCart" data-product-id="{{ $product->id }}"><a href="#"><span class="icon_bag_alt"></span></a></li>
                        </form>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6><a href="#">{{ $product->product_name }}</a></h6>
                        <div class="rating">
                            @if($product->reviews && $product->reviews->isNotEmpty())
                                @foreach($product->reviews as $review)
                                    <div class="review">
                                        <div class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fa fa-star"></i> <!-- Filled star -->
                                                @else
                                                    <i class="fa fa-star-o"></i> <!-- Empty star -->
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Display empty stars if no reviews -->
                                <i class="fa fa-star-o"></i> <!-- Empty star -->
                                <i class="fa fa-star-o"></i> <!-- Empty star -->
                                <i class="fa fa-star-o"></i> <!-- Empty star -->
                                <i class="fa fa-star-o"></i> <!-- Empty star -->
                                <i class="fa fa-star-o"></i> <!-- Empty star -->
                            @endif
                        </div>
                        <div class="product__price">
                            ₦ 
                            @if($product->product_discount === null || $product->product_discount == 0)
                                {{ number_format($product->product_price, 2) }}
                            @else
                                {{ number_format($product->product_price - $product->product_discount, 2) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->


@include('components.footer')
</html>



<script type="text/javascript">
document.querySelectorAll('.view-product-btn').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        window.location.href = '/product-details/' + productId;
    });
});
</script>

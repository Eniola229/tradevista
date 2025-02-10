<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductsAttribute;
use App\Models\ProductsImage;
use App\Models\Review;
use Illuminate\Support\Facades\Http;


$products = Product::where('status', 'ACTIVE')
                    ->with('category')
                    ->with('reviews')
                    ->inRandomOrder() 
                    ->take(10)         
                    ->get();

$bestsellers = Product::where('status', 'ACTIVE')
                    ->with('category')
                    ->with('reviews')
                    ->whereHas('reviews', function ($query) {
                        // Ensure there are more than 5 reviews associated with the product
                        $query->havingRaw('COUNT(*) > 5');
                    })
                    ->inRandomOrder() 
                    ->take(5) 
                    ->get();
$features = Product::where('status', 'ACTIVE')
                    ->where('is_featured', 'YES')
                    ->with('category')
                    ->with('reviews')
                    ->inRandomOrder() 
                    ->take(5) 
                    ->get();

$hots = Product::where('status', 'ACTIVE')
                    ->with('category')
                    ->with('reviews')
                    ->inRandomOrder() 
                    ->take(5) 
                    ->get();


  $categories = Category::get();
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

    <!-- Categories Section Begin -->
<!--     <section class="categories">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="categories__item categories__large__item set-bg"
                    data-setbg="img/categories/in.jpg">
                    <div class="categories__text">
                        <h1>Oraimo Traveler 4</h1>
                        <p>S20000mAh 10.5W Power Banks</p>
                        <a href="#">Shop now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="img/categories/kettle.png">
                            <!-- <div class="categories__text">
                                <h4>Accessories</h4>
                                <p>792 items</p>
                                <a href="#">Shop now</a>
                            </div>  --
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="img/categories/computers.jpg">
                            <!-- <div class="categories__text">
                                <h4>Accessories</h4>
                                <p>792 items</p>
                                <a href="#">Shop now</a>
                            </div>  --
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="img/categories/ara.png">
                            <!-- <div class="categories__text">
                                <h4>Accessories</h4>
                                <p>792 items</p>
                                <a href="#">Shop now</a>
                            </div>  --
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="img/categories/category-5.jpg">
                            <!-- <div class="categories__text">
                                <h4>Accessories</h4>
                                <p>792 items</p>
                                <a href="#">Shop now</a>
                            </div>  --
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- Banner Section Begin -->
<section class="banner set-bg" data-setbg="img/banner.png">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8 m-auto">
                <div class="banner__slider owl-carousel">
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>Connect. Explore. Discover.</span>
                            <h1>Unmatched Deals Await</h1>
                            <a href="{{ url('/products') }}">Start Shopping</a>
                        </div>
                    </div>
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>Shop Smart, Live Better</span>
                            <h1>Everything You Need</h1>
                            <a href="{{ url('/products') }}">Browse Now</a>
                        </div>
                    </div>
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>Your Marketplace. Your Rules.</span>
                            <h1>Find What Moves You</h1>
                            <a href="{{ url('/products') }}">Explore Today</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->
<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="section-title">
                    <h4>New products</h4>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <ul class="filter__controls">
                    <li class="active" data-filter="*">All</li>
                    @foreach($categories as $category)
                        <li data-filter=".{{ \Str::slug($category->name) }}">{{ $category->name }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
        <div class="row property__gallery">
      @foreach($products as $product)
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
<!-- Product Section End -->

<!-- Trend Section Begin -->
<section class="trend spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Hot Trend</h4>
                    </div>
                     @if($hots->isNotEmpty())
                     @foreach($hots as $hot)
                    <div class="trend__item">
                        <div class="trend__item__pic">
                            <img src="{{ $hot->image_url }}" alt="{{ $hot->product_name }}" width="70">
                        </div>
                        <div class="trend__item__text">
                            <h6>{{ $hot->product_name }}</h6>
                             @if($hot->reviews && $hot->reviews->isNotEmpty())
                                @foreach($hot->reviews as $review)
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
                            <div class="product__price">
                                ₦ 
                                @if($hot->product_discount === null || $hot->product_discount == 0)
                                    {{ number_format($hot->product_price, 2) }}
                                @else
                                    {{ number_format($hot->product_price - $hot->product_discount, 2) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                        @else
                         <div class="trend__item">
                            <p>NO PRODUCT UNDER THIS CATEGORY</p>
                        </div>
                        @endif    
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Best seller</h4>
                    </div>
                    @if($bestsellers->isNotEmpty())
                    @foreach($bestsellers as $bestseller)
                    <div class="trend__item">
                        <div class="trend__item__pic">
                              <img src="{{ $bestseller->image_url }}" width="70" alt="">
                        </div>
                        <div class="trend__item__text">
                            <h6>{{ $bestseller->product_name }}</h6>
                            <div class="rating">
                            @if($bestseller->reviews && $bestseller->reviews->isNotEmpty())
                                @foreach($bestseller->reviews as $review)
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
                                ₦ 
                                @if($bestseller->product_discount === null || $bestseller->product_discount == 0)
                                    {{ number_format($bestseller->product_price, 2) }}
                                @else
                                    {{ number_format($bestseller->product_price - $bestseller->product_discount, 2) }}
                                @endif
                        </div>
                    </div>
                @endforeach
                @else
                 <div class="trend__item">
                    <p>NO PRODUCT UNDER THIS CATEGORY</p>
                </div>
                @endif
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Feature</h4>
                    </div>
                 @if($features->isNotEmpty())
                @foreach($features as $featured)
                    <div class="trend__item">
                        <div class="trend__item__pic">
                            <img src="{{ $featured->image_url }}" width="70" alt="">
                        </div>
                        <div class="trend__item__text">
                            <h6>{{ $featured->product_name }}</h6>
                            <div class="rating">
                             @if($featured->reviews && $featured->reviews->isNotEmpty())
                                @foreach($featured->reviews as $review)
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
                                ₦ 
                                @if($featured->product_discount === null || $featured->product_discount == 0)
                                    {{ number_format($featured->product_price, 2) }}
                                @else
                                    {{ number_format($featured->product_price - $featured->product_discount, 2) }}
                                @endif
                        </div>
                    </div>
                        @endforeach
                        @else
                         <div class="trend__item">
                            <p>NO PRODUCT UNDER THIS CATEGORY</p>
                        </div>
                        @endif  
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Discount Section Begin -->
<section class="discount">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="discount__pic">
                    <img src="img/tradevista.png" style="height: 400px;" alt="">
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="discount__text">
                    <div class="discount__text__title">
                        <span>Discount</span>
                        <h2>Collection 2025</h2>
                        <h5><span>Sale</span> 50%</h5>
                    </div>
                    <div class="discount__countdown" id="countdown-time">
                        <div class="countdown__item">
                            <span>22</span>
                            <p>Days</p>
                        </div>
                        <div class="countdown__item">
                            <span>18</span>
                            <p>Hour</p>
                        </div>
                        <div class="countdown__item">
                            <span>46</span>
                            <p>Min</p>
                        </div>
                        <div class="countdown__item">
                            <span>05</span>
                            <p>Sec</p>
                        </div>
                    </div>
                    <a href="{{ url('/products') }}">Shop now</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Discount Section End -->

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-car"></i>
                    <h6>Low Shipping Fee</h6>
                    <p>For all order over ₦10k</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-money"></i>
                    <h6>Money Back Guarantee</h6>
                    <p>If goods have Problems</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-support"></i>
                    <h6>Online Support 24/7</h6>
                    <p>Dedicated support</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-headphones"></i>
                    <h6>Payment Secure</h6>
                    <p>100% secure payment</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Instagram Begin -->
<!-- <div class="instagram">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-1.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-2.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-3.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-4.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-5.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/insta-6.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="#">@ ashion_shop</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- Instagram End -->


@include('components.footer')
</html>
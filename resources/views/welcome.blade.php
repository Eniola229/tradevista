<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;
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
    ->with('category', 'reviews')
    ->whereHas('reviews', function ($query) {
        // Group by product_id to count reviews per product
        $query->select('product_id')
              ->groupBy('product_id')
              ->havingRaw('COUNT(*) > 5');
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
  $notifications = Notification::get();
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
@php
    $validNotifications = $notifications
        ->where('expiry_date', '>', now())
        ->filter(function ($notification) {
            return $notification->type === 'GENERAL' || (auth()->check() && $notification->type === 'CUSTOMERS');
        })
        ->values();
@endphp

@if ($validNotifications->isNotEmpty())
    <div id="notificationWrapper" style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 1050; width: 500px; max-width: 90vw;">
        <div id="notificationCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($validNotifications as $key => $notification)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <div class="alert alert-primary shadow-lg p-4 rounded d-flex flex-column text-white bg-opacity-75" style="background-color: #053262;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <i class="fa fa-bell fa-lg me-2"></i>
                                <button type="button" class="btn-close btn-close-white" id="closeNotificationBtn" style="background: none; color: red; font-weight: bold; border: none; font-size: 30px;">×</button>
                            </div>

                            @if(!empty($notification->type))
                                <span class="badge bg-warning text-dark align-self-start mb-3 px-3 py-2 fs-6 rounded-pill">{{ $notification->type }}</span>
                            @endif

                            @if(!empty($notification->image_url))
                                <div class="text-center mb-3">
                                    <img src="{{ $notification->image_url }}"
                                         class="img-fluid rounded"
                                         style="max-height: 200px; object-fit: cover;"
                                         alt="Notification Image">
                                </div>
                            @endif

                            <h4 class="fw-bold text-center text-white fs-4 mb-2">Special Notification</h4>
                            <p class="fw-bold text-center fs-5">{{ $notification->description }}</p>

                            @if($notification->links)
                                <div class="text-center mt-3">
                                    <a href="{{ $notification->links }}" class="btn btn-secondary btn-sm px-4 py-2 fw-bold" target="_blank">
                                        Learn More
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#notificationCarousel" data-bs-slide="prev"
                style="width: 35px; height: 35px; background-color: #000; border-radius: 50%; top: 50%; transform: translateY(-50%); opacity: 0.8;">
                <span class="carousel-control-prev-icon" aria-hidden="true"
                    style="background-size: 70% 70%; filter: invert(1);"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#notificationCarousel" data-bs-slide="next"
                style="width: 35px; height: 35px; background-color: #000; border-radius: 50%; top: 50%; transform: translateY(-50%); opacity: 0.8;">
                <span class="carousel-control-next-icon" aria-hidden="true"
                    style="background-size: 70% 70%; filter: invert(1);"></span>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const notificationWrapper = document.getElementById("notificationWrapper");
            const closeBtn = document.getElementById("closeNotificationBtn");

            // Auto-hide after 10 seconds
            setTimeout(() => {
                notificationWrapper.style.display = "none";
            }, 10000);

            // Manual close
            closeBtn.addEventListener("click", () => {
                notificationWrapper.style.display = "none";
            });

            // Initialize carousel
            new bootstrap.Carousel(document.querySelector("#notificationCarousel"), {
                interval: 8000,
                pause: "hover",
                wrap: true
            });
        });
    </script>
@endif

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

<!---Start of Contest banner--->
<section class="discount mt-3 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="discount__pic">
                    <img src="{{ asset('https://res.cloudinary.com/di2ci6rz8/image/upload/v1748966428/notifications/xvt7rfxgvrx1frlxljjb.jpg') }}" style="height: 400px;" alt="Giveaway">
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="discount__text">
                    <div class="discount__text__title">
                        <span>Public Notice</span>
                        <h2>Giveaway Contest 2025</h2>
                        <h5><span>Join</span> & Win Big!</h5>
                    </div>
                    <p>
                        The 2025 Giveaway is live! Register now to compete and stand a chance to win exciting prizes 
                        like a fridge, iron, or toaster. Limited slots available – only 50 contestants allowed!
                    </p>
                    <div class="mt-4 d-flex">
                        <a href="{{ url('results') }}"><button class="site-btn me-2">Live Results</button></a>
                        <a href="{{ route('dashboard') }}"><button  class="site-btn">Register</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!----End of contest banner---->

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
                <ul class="filter__controls ">
                    <li class="active" data-filter="*">All</li>
                    @foreach($categories as $category)
                        <li data-filter=".{{ \Str::slug($category->name) }}" class="p-2">{{ $category->name }}</li>
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
                        @php
                            $averageRating = round($product->reviews->avg('rating')); // Calculate the average rating
                        @endphp
                        <div class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $averageRating)
                                    <i class="fa fa-star"></i> <!-- Filled star -->
                                @else
                                    <i class="fa fa-star-o"></i> <!-- Empty star -->
                                @endif
                            @endfor
                        </div>
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
                                {{ number_format($product->product_discount, 2) }}
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
                                @php
                                    $averageRating = round($hot->reviews->avg('rating')); // Calculate the average rating
                                @endphp
                                <div class="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $averageRating)
                                            <i class="fa fa-star"></i> <!-- Filled star -->
                                        @else
                                            <i class="fa fa-star-o"></i> <!-- Empty star -->
                                        @endif
                                    @endfor
                                </div>
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
                                    {{ number_format($hot->product_discount, 2) }}
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
                                @php
                                    $averageRating = round($bestseller->reviews->avg('rating')); // Calculate the average rating
                                @endphp
                                <div class="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $averageRating)
                                            <i class="fa fa-star"></i> <!-- Filled star -->
                                        @else
                                            <i class="fa fa-star-o"></i> <!-- Empty star -->
                                        @endif
                                    @endfor
                                </div>
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
                                    {{ number_format($bestseller->product_discount, 2) }}
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
                                @php
                                    $averageRating = round($featured->reviews->avg('rating')); // Calculate the average rating
                                @endphp
                                <div class="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $averageRating)
                                            <i class="fa fa-star"></i> <!-- Filled star -->
                                        @else
                                            <i class="fa fa-star-o"></i> <!-- Empty star -->
                                        @endif
                                    @endfor
                                </div>
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
                                    {{ number_format($featured->product_discount, 2) }}
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
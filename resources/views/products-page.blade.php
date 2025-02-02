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
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="shop__sidebar">
                        <div class="sidebar__categories">
                            <div class="section-title">
                                <h4>Categories</h4>
                            </div>
                            <div class="categories__accordion">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-heading active">
                                              <li class="filter-item" data-filter="all" style="list-style: none;">
                                            <a href="#">All</a>
                                        </li>
                                        </div>
                                    </div>
                                @foreach($categories as $category)
                                    <div class="card">
                                        <div class="card-heading active">
                                              <li class="filter-item" data-filter="{{ \Str::slug($category->name) }}" style="list-style: none;">
                                            <a href="#">{{ $category->name }}</a>
                                        </li>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="sidebar__filter">
                            <div class="section-title">
                                <h4>Shop by price</h4>
                            </div>
                            <div class="filter-range-wrap">
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="33" data-max="99"></div>
                                <div class="range-slider">
                                    <div class="price-input">
                                        <p>Price:</p>
                                        <input type="text" id="minamount">
                                        <input type="text" id="maxamount">
                                    </div>
                                </div>
                            </div>
                            <a href="#">Filter</a>
                        </div>
                      
                    </div>
                </div>
                <div class="col-lg-9 col-md-9">
            @if(!empty($query))
                <p class="mb-4">Showing results for: <strong>{{ $query }}</strong></p>
            @endif

         <div class="row">
             @if($products->isNotEmpty())
          @foreach($products as $product)
            <div class="col-lg-4 col-md-4 col-sm-6 mix {{ \Str::slug($product->category->name) }} view-product-btn" id="viewProductBtn" data-product-id="{{ $product->id }}">
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
        @else 
         <div class="container text-center">
            <h4>No Product Found.</h4>
            <div class="cart__btn update__btn" style="justify-content: center; align-items: center; margin: auto;">
             <a href="{{ url('products') }}" ><span class="icon_loading"></span>Products</a>
         </div>
        @endif
                
        <div class="col-lg-12 text-center">
            <div class="pagination__option">
                @if ($products->lastPage() > 1)
                    @if (!$products->onFirstPage())
                        <a href="{{ $products->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a>
                    @endif

                    @for ($i = 1; $i <= $products->lastPage(); $i++)
                        <a href="{{ $products->url($i) }}" class="{{ $i == $products->currentPage() ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"><i class="fa fa-angle-right"></i></a>
                    @endif
                @endif
            </div>
        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->


@include('components.footer')
</html>



<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function () {
    const filterItems = document.querySelectorAll(".filter-item");
    const products = document.querySelectorAll(".mix");

    filterItems.forEach(item => {
        item.addEventListener("click", function () {
            // Remove active class from all filter items
            filterItems.forEach(i => i.classList.remove("active"));

            // Add active class to the clicked filter item
            this.classList.add("active");

            const filter = this.getAttribute("data-filter");

            // Show or hide products based on the filter
            products.forEach(product => {
                if (filter === "all" || product.classList.contains(filter)) {
                    product.style.display = "block";
                } else {
                    product.style.display = "none";
                }
            });
        });
    });
});

</script>

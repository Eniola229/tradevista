  <?php
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Wishlist;
use App\Models\Product;

$user = auth()->user();
$cart = [];
$totalQuantity = 0;
$totalAmount = 0;

if ($user) {
    // User is logged in, get cart based on user_id
    $cartItems = Cart::where('user_id', $user->id)
        ->get()
        ->unique('product_id'); // Ensure unique products by product_id

    foreach ($cartItems as $item) {
        // Get the discounted price
        $discountedPrice = Product::getDiscountedPrice($item->product_id);

        // Determine the final price
        $finalPrice = $discountedPrice > 0 ? $discountedPrice : $item->product->product_price;

        // Add unique product to cart array
        $cart[$item->product_id] = [
            'id' => $item->product_id,
            'product_name' => $item->product->product_name,
            'product_price' => $finalPrice,
            'quantity' => $item->quantity,
            'total' => $item->quantity * $finalPrice,
            'image_url' => $item->product->image_url,
            'size' => $item->size,
        ];
    }
} else {
    // User is not logged in, get cart from session (Database using session id)
    $sessionId = Session::getId();
    $cartItems = Cart::where('session_id', $sessionId)
        ->get()
        ->unique('product_id'); // Ensure unique products by product_id

    foreach ($cartItems as $item) {
        // Get the discounted price
        $discountedPrice = Product::getDiscountedPrice($item->product_id);

        // Determine the final price
        $finalPrice = $discountedPrice > 0 ? $discountedPrice : $item->product->product_price;

        // Add unique product to cart array
        $cart[$item->product_id] = [
            'id' => $item->product_id,
            'product_name' => $item->product->product_name,
            'product_price' => $finalPrice,
            'quantity' => $item->quantity,
            'total' => $item->quantity * $finalPrice,
            'image_url' => $item->product->image_url,
            'size' => $item->size,
        ];
    }
}

// Calculate the total quantity and amount for unique products
$totalQuantity = array_sum(array_column($cart, 'quantity'));
$totalAmount = array_sum(array_column($cart, 'total'));

if (Auth::check()) {
    $wishLiistCount = Wishlist::where('user_id', $user->id)->count();
} else {
    $wishLiistCount = '0';
}

$categories = Category::get();

?>
    <header class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-lg-2">
                    <div class="header__logo">
                        <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-7">
                    <nav class="header__menu">
                       <ul>
                        <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li>
                        <li class="{{ Request::is('products*') ? 'active' : '' }}"><a href="{{ url('products') }}">Products</a></li>
                        <li class="{{ Request::is('blog*') ? 'active' : '' }}"><a href="{{ url('blog') }}">Blog</a></li>
                        <li class="{{ Request::is('about') ? 'active' : '' }}"><a href="{{ url('about') }}">About</a></li>
                        <li class="{{ Request::is('contact') ? 'active' : '' }}"><a href="{{ url('contact') }}">Contact</a></li>
                    </ul>

                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__right">
                        <div class="header__right__auth">
                            @if(Auth::check())
                                <!-- Show profile if the user is authenticated -->
                              @if(auth()->check() && auth()->user()->email_verified_at)
                                    <a href="{{ url('dashboard') }}">Dashboard</a>
                                @else
                                    <script>
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Account Not Verified',
                                            text: 'Please verify your account to access the dashboard.',
                                            confirmButtonText: 'OK'
                                        });
                                    </script>
                                @endif
                            @else
                                <!-- Show login and register links if the user is not authenticated -->
                                <a href="{{ url('login') }}">Login</a>
                                <a href="{{ url('register') }}">Register</a>
                            @endif
                        </div>
                        <ul class="header__right__widget">
                            <li><span class="icon_search search-switch"></span></li>
                            <li><a href="{{ url('wishlist') }}"><span class="icon_heart_alt"></span>
                                <div class="tip">{{ $wishLiistCount }}</div>
                            </a></li>
                            <li><a href="{{ url('carts') }}"><span class="icon_bag_alt"></span>
                                <div class="tip">{{ $totalQuantity }}</div>
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="canvas__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
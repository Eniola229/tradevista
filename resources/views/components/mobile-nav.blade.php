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
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
            <li><a href="{{ url('wishlist') }}"><span class="icon_heart_alt"></span>
                <div class="tip">{{ $wishLiistCount }}</div>
            </a></li>
            <li><a href="{{ url('carts') }}"><span class="icon_bag_alt"></span>
                <div class="tip">{{ $totalQuantity }}</div>
            </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}" width="80" height="80" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth"> 
                              @if(Auth::check())
                                <!-- Show profile if the user is authenticated -->
                                <a href="{{ url('dashboard') }}">Dashbaord</a>
                            @else
                                <!-- Show login and register links if the user is not authenticated -->
                                <a href="{{ url('login') }}">Login</a>
                                <a href="{{ url('register') }}">Register</a>
                            @endif
        </div>
    </div>
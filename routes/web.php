<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//ADMIN
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminRefererController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\WIthdraweralController;

//USER
use App\Http\Controllers\Front\SetupController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\ShippingController;
use App\Http\Controllers\Front\RefererController;
use App\Http\Controllers\Front\ProductPageController;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\SupportController;
use App\Http\Controllers\Front\WithdrawController;
use App\Http\Controllers\LocationController;


Route::get('/', function () {
    return view('welcome');
});

   
Route::get('checkout/get-shipping-fee', [ShippingController::class, 'getPickupRates']);


Route::get('/contact', [BlogController::class, 'contact']);
Route::post('/contact', [BlogController::class, 'storeContact']);
Route::post('/newsletter', [BlogController::class, 'storeNewsletter']);
Route::get('/blog', [BlogController::class, 'blogs']);
Route::get('/about', [BlogController::class, 'about']);

//WISHLIST
Route::prefix('wishlist')->controller(WishlistController::class)->group(function () {
    Route::get('/', 'wishlistIndex');
    Route::get('addWishlist', 'addWishlist')->name('addWishlist');
    Route::get('removeWishlist', 'removeWishlist')->name('removeWishlist');
    Route::get('clearWishlist', 'clearWishlist')->name('clearWishlist');
});



// Products Routes
Route::prefix('products')->controller(ProductPageController::class)->group(function () {
    Route::get('/', 'ProductsPage')->name('products');
    Route::get('seller-products', 'sellerProducts');
    Route::get('flash-sale', 'flashSale');
   //Route::get('details/{id}', 'single');
    Route::get('compare', 'compareProduct');
    Route::get('category/{id}', 'categoryProducts');
    Route::get('search', 'products')->name('search');
    Route::get('add-to-cart', 'addToCart')->name('add-to-shopping-cart');
    Route::get('review-a-product', 'review')->name('review-product');
});
 Route::get('product-details/{id}', [ProductPageController::class, 'ProductDetails'])->name('delete-product');
// Cart Routes
Route::prefix('carts')->controller(CartController::class)->group(function () {
    Route::get('/', 'carts')->name('cart');
    Route::get('carts/clear-cart', 'clearCart')->name('clear-cart');
    Route::get('/cart/remove', 'remove_item')->name('cart.remove');
    Route::get('/cart/increase', 'update_increase_cart')->name('update.increase.cart');
    Route::get('/cart/decrease', 'update_decrease_cart')->name('update.decrease.cart');

});

//COUNTRY AND CITIES 
Route::get('/location-form', [LocationController::class, 'showForm'])->name('location.form');
Route::get('/get-cities', [LocationController::class, 'getCities'])->name('location.cities');

Route::middleware('auth')->group(function () {
    //PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/address', [ProfileController::class, 'storeaddress']);
    Route::post('/address/{id}', [ProfileController::class, 'updateaddress']);
    Route::put('/avatar', [ProfileController::class, 'updateAvatar']);
    Route::put('/setup/{id}', [SetupController::class, 'update'])->name('update.setup');
    //DASHBOARD
    Route::get('dashboard', [SetupController::class, 'setup'])->name('dashboard');
    //SETUP
    Route::post('/create-setup', [SetupController::class, 'createSetup'])->name('create.setup');
    Route::get('/payment/callback', [SetupController::class, 'paymentCallback'])->name('payment.callback');
    //REFERER
    Route::get('user/referer', [RefererController::class, 'index'])->name('user.referer');
    //PRODUCTS
    Route::get('/user/products', [ProductController::class, 'products'])->name('products');
    Route::match(['get', 'post'], 'add-edit-product/{id?}', [ProductController::class, 'addEditProduct'])->name('products.add');
    Route::put('add-edit-product/{id}', [ProductController::class, 'addEditProduct'])->name('products.edit');
    Route::get('user/view/product/{id}', [ProductPageController::class, 'viewProduct'])->name('products.view');
    Route::post('user/add/attribute/{id}', [ProductController::class, 'addAttributes'])->name('add-attribute');
    Route::post('user/add/images/{id}', [ProductController::class, 'addImages'])->name('add-images');
    Route::post('user/delete/product/{id}', [ProductController::class, 'deleteProduct'])->name('delete-product');
    //SUPPORT 
    Route::get('user/support', [SupportController::class, 'index'])->name('support');
    Route::post('/support-tickets', [SupportController::class, 'store'])->name('support.ticket.submit');
    Route::get('/support-tickets/{id}', [SupportController::class, 'show']);
    Route::put('/support-tickets/{id}', [SupportController::class, 'update']);
    Route::delete('/support-tickets/{id}', [SupportController::class, 'destroy']);
    //REDRAW
    Route::get('user/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
    Route::post('user/withdraw', [WithdrawController::class, 'withdraw'])->name('user-withdraw');
    Route::post('/withdraw/upload-receipt/{id}', [WithdrawController::class, 'uploadReceipt'])->name('withdraw.upload-receipt');
    // Checkout Routes
    Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('checkout.index');
    });
    
    
});

//ADMIN
Route::get('admin/login', [AdminAuthController::class, 'index'])->name('admin-login');
Route::post('post/login', [AdminAuthController::class, 'postLogin'])->name('admin-login.post'); 

Route::get('admin/logout', [AdminAuthController::class, 'logout'])->name('admin-logout');

Route::middleware('auth:admin')->prefix('admin')->group(function () {
 Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin-dashboard'); 
 Route::get('/team', [TeamController::class, 'team'])->name('admin-team'); 
 Route::get('/edit/{id}', [TeamController::class, 'edit'])->name('admin-team-edit'); 
 Route::get('/delete/{id}', [TeamController::class, 'delete'])->name('admin-team-edit'); 
 Route::post('/add', [AdminAuthController::class, 'postRegistration'])->name('admin-team-add'); 
 Route::get('/add', [TeamController::class, 'add'])->name('team-add'); 
 Route::get('/categories', [CategoryController::class, 'index'])->name('categories-view'); 
 Route::get('/add/catagory', [CategoryController::class, 'add'])->name('categories-add'); 
 Route::post('/add/catagory', [CategoryController::class, 'addCategory'])->name('admin-add-category'); 
 Route::get('/category/delete/{id}', [CategoryController::class, 'delete'])->name('admin-delete-category');
 Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin-category-edit');
 Route::get('/referer', [AdminRefererController::class, 'index'])->name('user.referer');
 Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products');
 Route::get('/view/product/{id}', [AdminProductController::class, 'view'])->name('admin.view-products');
 Route::get('/change-product-status', [AdminProductController::class, 'changeStatus']);
 Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
 Route::get('/support', [SupportTicketController::class, 'index'])->name('support');
 Route::post('/support/answer/{id}', [SupportTicketController::class, 'answer'])->name('support.answer');
 Route::get('/withdraw', [WIthdraweralController::class, 'index'])->name('withdraw');
 Route::post('/upload-receipt', [WIthdraweralController::class, 'uploadReceipt'])->name('admin.uploadReceipt');
});

require __DIR__.'/auth.php';

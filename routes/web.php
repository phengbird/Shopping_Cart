<?php

use App\Http\Livewire\Admin\AdminAddCategoryComponent;
use App\Http\Livewire\Admin\AdminAddCouponsComponent;
use App\Http\Livewire\Admin\AdminAddHomeSliderComponent;
use App\Http\Livewire\Admin\AdminAddProductComponent;
use App\Http\Livewire\Admin\AdminCategoryComponent;
use App\Http\Livewire\Admin\AdminDashBoardComponent;
use App\Http\Livewire\Admin\AdminEditCategoryComponent;
use App\Http\Livewire\Admin\AdminEditHomeSliderComponent;
use App\Http\Livewire\Admin\AdminEditProductComponent;
use App\Http\Livewire\Admin\AdminHomeCategoryComponent;
use App\Http\Livewire\Admin\AdminHomeSliderComponent;
use App\Http\Livewire\Admin\AdminProductComponent;
use App\Http\Livewire\Admin\AdminSaleComponent;
use App\Http\Livewire\Admin\AdminCouponsComponent;
use App\Http\Livewire\Admin\AdminEditCouponsComponent;
use App\Http\Livewire\Admin\AdminOrderComponent;
use App\Http\Livewire\Admin\AdminOrderDetailsComponent;
use App\Http\Livewire\CartComponent;
use App\Http\Livewire\CategoryComponent;
use App\Http\Livewire\CheckoutComponent;
use App\Http\Livewire\DetailsComponent;
use App\Http\Livewire\HomeComponent;
use App\Http\Livewire\SearchComponent;
use App\Http\Livewire\ShopComponent;
use App\Http\Livewire\ThankyouComponent;
use App\Http\Livewire\User\UserDashBoardComponent;
use App\Http\Livewire\User\UserOrdersComponent;
use App\Http\Livewire\User\UserOrdersDetailsComponent;
use App\Http\Livewire\User\UserReviewComponent;
use App\Http\Livewire\WishlistComponent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',HomeComponent::class);
Route::get('/shop',ShopComponent::class);
Route::get('/cart',CartComponent::class)->name('product.cart');
Route::get('/checkout',CheckoutComponent::class)->name('checkout');
Route::get('/thankyou',ThankyouComponent::class)->name('thankyou');
Route::get('/search',SearchComponent::class)->name('product.search');
Route::get('/wishlist',WishlistComponent::class)->name('product.wishlist');
Route::get('/product/{slug}',DetailsComponent::class)->name('product.details');
Route::get('/product_category/{category_slug}',CategoryComponent::class)->name('product.category');


// for user or customer
Route::middleware(['auth:sanctum','verified'])->group(function (){
    Route::get('/user/dashboard',UserDashBoardComponent::class)->name('user.dashboard');

    //orders
    Route::get('/user/orders',UserOrdersComponent::class)->name('user.orders');
    Route::get('/user/orders/details/{id}',UserOrdersDetailsComponent::class)->name('user.orderdetails');

    //review
    Route::get('/user/review/{id}',UserReviewComponent::class)->name('user.review');
});

// for admin
Route::middleware(['auth:sanctum','verified','authadmin'])->group(function (){
    //home
    Route::get('/admin/dashboard',AdminDashBoardComponent::class)->name('admin.dashboard');
    Route::get('/admin/homecategories',AdminHomeCategoryComponent::class)->name('admin.homecategories');
    
    //category
    Route::get('/admin/categories',AdminCategoryComponent::class)->name('admin.categories');
    Route::get('/admin/categories/add',AdminAddCategoryComponent::class)->name('admin.addcategory');
    Route::get('/admin/categories/edit/{category_slug}',AdminEditCategoryComponent::class)->name('admin.editcategory');
    
    //product
    Route::get('/admin/products',AdminProductComponent::class)->name('admin.products');
    Route::get('/admin/products/add',AdminAddProductComponent::class)->name('admin.addproduct');
    Route::get('/admin/products/edit/{product_slug}',AdminEditProductComponent::class)->name('admin.editproduct');

    //home slider
    Route::get('/admin/slider',AdminHomeSliderComponent::class)->name('admin.homeslider');
    Route::get('/admin/slider/add',AdminAddHomeSliderComponent::class)->name('admin.addhomeslider');
    Route::get('/admin/slider/edit/{slider_id}',AdminEditHomeSliderComponent::class)->name('admin.edithomeslider');

    //sale
    Route::get('/admin/sale',AdminSaleComponent::class)->name('admin.sale');

    //coupons
    Route::get('/admin/coupons',AdminCouponsComponent::class)->name('admin.coupons');
    Route::get('/admin/coupon/edit/{coupon_id}',AdminEditCouponsComponent::class)->name('admin.editcoupon');
    Route::get('/admin/coupon/add',AdminAddCouponsComponent::class)->name('admin.addcoupon');

    //order
    Route::get('/admin/orders',AdminOrderComponent::class)->name('admin.orders');
    Route::get('/admin/orders/details/{id}',AdminOrderDetailsComponent::class)->name('admin.orderdetails');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


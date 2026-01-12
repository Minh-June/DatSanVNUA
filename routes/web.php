<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PickController;
use App\Http\Controllers\Client\SuccessController;
use App\Http\Controllers\Client\PayController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\NewsBoardController;
use App\Http\Controllers\Client\StoresBoardController;
use App\Http\Controllers\Client\StoreDetailController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CartPayController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContractorController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\YardController;
use App\Http\Controllers\Admin\TimeController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsTypeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderDetailController;
use App\Http\Controllers\Admin\FixedOrderController;
use App\Http\Controllers\Admin\FixedOrderDetailController;
use App\Http\Controllers\Admin\StoresController;
use App\Http\Controllers\Admin\ProductTypesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ProductSizesController;
use App\Http\Controllers\Admin\PayInfoController;
use App\Http\Controllers\Admin\StatementController;
use App\Http\Controllers\Admin\ProductOrderController;
use App\Http\Controllers\Admin\ProductOrderDetailController;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route trang chủ
Route::get('/', [HomeController::class, 'index'])->name('view');
Route::get('/trang-chu', [HomeController::class, 'home'])->name('trang-chu');
Route::get('/trang-chu/tim-kiem', [HomeController::class, 'search'])->name('tim-kiem');

// Route đăng ký
Route::get('/dang-ky', [RegisterController::class, 'showRegistrationForm'])->name('dang-ky');
Route::post('/dang-ky', [RegisterController::class, 'register'])->name('dang-ky');

// Route đăng nhập
Route::get('/dang-nhap', [LoginController::class, 'showLoginForm'])->name('dang-nhap');
Route::post('/dang-nhap', [LoginController::class, 'login']);

// Route đăng xuất
Route::post('/dang-xuat', [LoginController::class, 'logout'])->name('dang-xuat');

// Route tài khoản cá nhân
Route::get('/thong-tin-tai-khoan', [AccountController::class, 'index'])->name('thong-tin-tai-khoan');
Route::get('/thong-tin-tai-khoan/lich-su-mua-hang', [AccountController::class, 'indexBuy'])->name('lich-su-mua-hang');
Route::get('/thong-tin-tai-khoan/thong-tin-ca-nhan', [AccountController::class, 'editInfor'])->name('thong-tin-ca-nhan');
Route::post('/thong-tin-tai-khoan/cap-nhat-thong-tin-ca-nhan', [AccountController::class, 'updateInfor'])->name('cap-nhat-thong-tin-ca-nhan');
Route::get('/thong-tin-tai-khoan/thay-doi-mat-khau', [AccountController::class, 'editPassword'])->name('thay-doi-mat-khau');
Route::post('/thong-tin-tai-khoan/thay-doi-mat-khau', [AccountController::class, 'updatePassword'])->name('cap-nhat-mat-khau');
Route::post('/thong-tin-tai-khoan/xoa-tai-khoan', [AccountController::class, 'delete'])->name('xoa-tai-khoan');
Route::delete('/xoa-anh-dai-dien', [AccountController::class, 'deleteAvatar'])->name('xoa-anh-dai-dien');
Route::get('/thong-tin-tai-khoan/lich-su-dat-san-co-dinh', [\App\Http\Controllers\Client\AccountController::class, 'fixedOrders'])->name('client.fixed-orders');

// Route đặt sân
Route::get('/trang-chu/dat-san/{yard_id}/{user_id?}', [PickController::class, 'index'])->name('dat-san');
Route::get('/get-booked-times', [PickController::class, 'getBookedTimes'])->name('getBookedTimes'); // Route lấy khung giờ đã đặt
Route::post('/luu-thong-tin-don-dat-san', [PickController::class, 'store'])->name('luu-thong-tin-don-dat-san');
Route::post('/luu-san-pham-pick', [PickController::class, 'storeProduct'])->name('luu-san-pham-pick');
Route::post('/luu-thue-theo-thang', [PickController::class, 'storeMonthlyRent'])->name('luu-thue-theo-thang');

// Route xác nhận đặt sân
Route::get('/xac-nhan-dat-san/{user_id?}', [SuccessController::class, 'index'])->name('xac-nhan-dat-san');
Route::delete('/xoa-don-tam-thoi', [SuccessController::class, 'delete'])->name('xoa-don-tam-thoi');

// Route thanh toán
Route::get('/thanh-toan', [PayController::class, 'index'])->name('thanh-toan');
Route::post('/thanh-toan/offline', [PayController::class, 'storeOffline'])->name('pay.offline');
Route::get('/thanh-toan/online/{owner_id}', [PayController::class, 'payNow'])->name('pay.now');
Route::post('/thanh-toan/online', [PayController::class, 'storeOnline'])->name('pay.online');
Route::get('/thanh-toan/het-han', [PayController::class, 'timeout'])->name('payment.timeout');

// Route danh sách các cửa hàng
Route::get('/danh-sach-cua-hang', [StoresBoardController::class, 'index'])->name('danh-sach-cua-hang');
// Route lưu thông tin sản phẩm vào giỏ hàng
Route::post('/luu-san-pham-storesboard', [StoresBoardController::class, 'store'])->name('luu-san-pham-storesboard');

// Route chi tiết cửa hàng
Route::get('/cua-hang/{store}', [StoreDetailController::class, 'index'])->name('chi-tiet-cua-hang');
// Route lưu thông tin sản phẩm vào giỏ hàng
Route::post('/luu-san-pham-storedetail', [StoreDetailController::class, 'store'])->name('luu-san-pham-storedetail');

// Route xem chi tiết sản phẩm
Route::get('/san-pham/{id}', [ProductDetailController::class, 'index'])->name('chi-tiet-san-pham');
// Route lưu thông tin sản phẩm vào giỏ hàng
Route::post('/luu-san-pham-productdetail', [ProductDetailController::class, 'store'])->name('luu-san-pham-productdetail');

Route::get('/debug-buys', function() {
    dd(session('buys'));
})->middleware('web');

// Route giỏ hàng
Route::get('/gio-hang', [CartController::class, 'index'])->name('gio-hang');
Route::delete('/xoa-san-pham-tam-thoi', [CartController::class, 'delete'])->name('xoa-san-pham-tam-thoi');
Route::post('/cap-nhat-so-luong', [CartController::class, 'updateQuantity'])->name('cap-nhat-so-luong');

// Route thanh toán giỏ hàng
Route::get('/thanh-toan-gio-hang', [CartPayController::class, 'index'])->name('thanh-toan-gio-hang');
Route::post('/thanh-toan-gio-hang/offline', [CartPayController::class, 'storeOffline'])->name('pay.product.offline');
Route::get('/thanh-toan-gio-hang/online/{owner_id}', [CartPayController::class, 'payNow'])->name('pay.product.now');
Route::post('/thanh-toan-gio-hang/online', [CartPayController::class, 'storeOnline'])->name('pay.product.online');

// Route xem tin tức
Route::get('/tin-tuc', [NewsBoardController::class, 'index'])->name('tin-tuc');
Route::get('/tin-tuc/{id}', [NewsBoardController::class, 'show'])->name('chi-tiet-tin-tuc');

// Route ADMIN - CHỦ THẦU - NHÂN VIÊN
Route::get('/admin', [AdminController::class, 'index'])->name('admin');

// Route quản lý người dùng
Route::get('/admin/quan-ly-nguoi-dung', [UserController::class, 'index'])->name('quan-ly-nguoi-dung');
Route::post('/admin/quan-ly-nguoi-dung/cap-nhat-vai-tro-nguoi-dung/{user_id}', [UserController::class, 'updateRole'])->name('cap-nhat-vai-tro-nguoi-dung');
Route::delete('/admin/quan-ly-nguoi-dung/xoa-nguoi-dung/{user_id}', [UserController::class, 'delete'])->name('xoa-nguoi-dung');
Route::get('/admin/quan-ly-nguoi-dung/xem-thong-tin-nguoi-dung/{user_id}', [UserController::class, 'show'])->name('xem-thong-tin-nguoi-dung');
Route::get('/admin/quan-ly-nguoi-dung/reset-mat-khau-nguoi-dung/{user_id}', [UserController::class, 'reset'])->name('reset-mat-khau-nguoi-dung');

// Route quản lý loại sân
Route::get('/admin/quan-ly-loai-san', [TypeController::class, 'index'])->name('quan-ly-loai-san');
Route::get('/admin/quan-ly-loai-san/them-loai-san', [TypeController::class, 'create'])->name('them-loai-san');
Route::post('/admin/quan-ly-loai-san/them-loai-san', [TypeController::class, 'store'])->name('luu-loai-san');
Route::get('/admin/quan-ly-loai-san/cap-nhat-loai-san/{type_id}', [TypeController::class, 'edit'])->name('cap-nhat-loai-san');
Route::post('/admin/quan-ly-loai-san/cap-nhat-loai-san/{type_id}', [TypeController::class, 'update'])->name('update.type');
Route::delete('/admin/quan-ly-loai-san/xoa-loai-san/{type_id}', [TypeController::class, 'delete'])->name('xoa-loai-san');

// Route quản lý sân
Route::get('/admin/quan-ly-san', [YardController::class, 'index'])->name('quan-ly-san');
Route::post('/admin/quan-ly-san/cap-nhat-trang-thai-san', [YardController::class, 'updateStatus'])->name('cap-nhat-trang-thai-san');
Route::get('/admin/quan-ly-san/them-san', [YardController::class, 'create'])->name('them-san');
Route::post('/admin/quan-ly-san/them-san', [YardController::class, 'store'])->name('luu-san');
Route::get('/admin/quan-ly-san/cap-nhat-san/{yard_id}', [YardController::class, 'edit'])->name('cap-nhat-san');
Route::post('/admin/quan-ly-san/cap-nhat-san/{yard_id}', [YardController::class, 'update'])->name('update.yard');
Route::delete('/admin/quan-ly-san/xoa-san/{yard_id}', [YardController::class, 'delete'])->name('xoa-san');

// Route quản lý thời gian sân
Route::get('/admin/quan-ly-thoi-gian-san', [TimeController::class, 'index'])->name('quan-ly-thoi-gian-san');
Route::post('/admin/quan-ly-thoi-gian-san/cap-nhat-trang-thai-thoi-gian-dat-san/{_id}', [TimeController::class, 'updateStatus'])->name('cap-nhat-trang-thai-thoi-gian-dat-san');
Route::get('/admin/quan-ly-thoi-gian-san/them-thoi-gian-san', [TimeController::class, 'create'])->name('them-thoi-gian-san');
Route::post('/admin/quan-ly-thoi-gian-san/them-thoi-gian-san', [TimeController::class, 'store'])->name('luu-thoi-gian-san');
Route::get('/admin/quan-ly-thoi-gian-san/cap-nhat-thoi-gian-san/{time_id}', [TimeController::class, 'edit'])->name('cap-nhat-thoi-gian-san');
Route::post('/admin/quan-ly-thoi-gian-san/cap-nhat-thoi-gian-san/{time_id}', [TimeController::class, 'update'])->name('update.time');
Route::delete('/admin/quan-ly-thoi-gian-san/xoa-thoi-gian-san/{time_id}', [TimeController::class, 'delete'])->name('xoa-thoi-gian-san');

// Route quản lý hình ảnh sân
Route::get('/admin/quan-ly-hinh-anh-san', [ImageController::class, 'index'])->name('quan-ly-hinh-anh-san');
Route::get('/admin/quan-ly-hinh-anh-san/them-hinh-anh-san', [ImageController::class, 'create'])->name('them-hinh-anh-san');
Route::post('/admin/quan-ly-hinh-anh-san/them-hinh-anh-san', [ImageController::class, 'store'])->name('luu-hinh-anh-san');
Route::get('/admin/quan-ly-hinh-anh-san/sua-hinh-anh-san/{image_id}', [ImageController::class, 'edit'])->name('cap-nhat-hinh-anh-san');
Route::post('/admin/quan-ly-hinh-anh-san/sua-hinh-anh-san/{image_id}', [ImageController::class, 'update'])->name('update.image');
Route::delete('/admin/quan-ly-hinh-anh-san/xoa-hinh-anh-san/{image_id}', [ImageController::class, 'delete'])->name('xoa-hinh-anh-san');

// Route quản lý đơn vị thầu sân
Route::get('/admin/quan-ly-don-vi-thau-san/thong-tin-don-vi-thau', [ContractorController::class, 'index'])->name('thong-tin-don-vi-thau');
Route::post('/admin/cap-nhat-thong-tin-thau', [ContractorController::class, 'update'])->name('cap-nhat-thong-tin-don-vi-thau');

// Route quản lý đơn đặt sân
Route::get('/admin/quan-ly-don-dat-san', [OrderController::class, 'index'])->name('quan-ly-don-dat-san');
Route::post('/admin/quan-ly-don-dat-san/cap-nhat-trang-thai-don-dat-san/{order_id}', [OrderController::class, 'updateStatus'])->name('cap-nhat-trang-thai-don-dat-san');
Route::get('/admin/quan-ly-don-dat-san/sua-thong-tin-don-dat-san/{order_id}', [OrderController::class, 'edit'])->name('cap-nhat-don-dat-san');
Route::delete('/admin/quan-ly-don-dat-san/xoa-don-dat-san/{order_id}', [OrderController::class, 'delete'])->name('xoa-don-dat-san');

// Route quản lý chi tiết đơn đặt sân
Route::get('/admin/quan-ly-don-dat-san/chi-tiet-don/sua-thong-tin-chi-tiet-don/{order_detail_id}', [OrderDetailController::class, 'index'])->name('cap-nhat-chi-tiet-don');
Route::get('/admin/yards-by-type/{type_id}', [OrderDetailController::class, 'getByType'])->name('yards.by.type');
Route::get('/admin/times-by-yard/{yard_id}/{date}', [OrderDetailController::class, 'getTimesByYard'])->name('times.by.yard');
Route::post('/admin/quan-ly-don-dat-san/chi-tiet-don/cap-nhat-thong-tin-chi-tiet-don/{order_detail_id}', [OrderDetailController::class, 'update'])->name('update.order_detail');
Route::delete('/admin/quan-ly-don-dat-san/chi-tiet-don/xoa-thong-tin-chi-tiet-don/{order_detail_id}', [OrderDetailController::class, 'delete'])->name('xoa-chi-tiet-don');

// Route quản lý đơn đặt sân cố định
Route::get('/admin/quan-ly-don-dat-san-co-dinh', [FixedOrderController::class, 'index'])->name('quan-ly-don-dat-san-co-dinh');
Route::post('/admin/quan-ly-don-dat-san-co-dinh/cap-nhat-trang-thai/{order_id}', [FixedOrderController::class, 'updateStatus'])->name('cap-nhat-trang-thai-don-dat-san-co-dinh');
Route::get('/admin/quan-ly-don-dat-san-co-dinh/{order_id}/sua', [FixedOrderController::class, 'edit'])->name('cap-nhat-don-dat-san-co-dinh');
Route::delete('/admin/quan-ly-don-dat-san-co-dinh/{order_id}', [FixedOrderController::class, 'delete'])->name('xoa-don-dat-san-co-dinh');

// Route quản lý chi tiết đơn đặt sân cố định
Route::get('/admin/quan-ly-don-dat-san-co-dinh/{order_id}/chi-tiet', [FixedOrderDetailController::class, 'index'])->name('cap-nhat-chi-tiet-don-dat-san-co-dinh');
// Ajax load sân theo loại và user hiện tại
Route::get('/admin/yards-by-type/{type_id}', [FixedOrderDetailController::class, 'getByType'])->name('yards.by.type');
Route::post('/admin/quan-ly-don-dat-san-co-dinh/{order_id}/chi-tiet', [FixedOrderDetailController::class, 'update'])->name('update.fixedorder_detail');

// Route quản lý cửa hàng
Route::get('/admin/quan-ly-cua-hang', [App\Http\Controllers\Admin\StoresController::class, 'index'])->name('quan-ly-cua-hang');
Route::post('/admin/quan-ly-cua-hang/cap-nhat-trang-thai/{store}', [App\Http\Controllers\Admin\StoresController::class, 'updateStatus'])->name('cap-nhat-trang-thai-cua-hang');
Route::get('/admin/quan-ly-cua-hang/them', [App\Http\Controllers\Admin\StoresController::class, 'create'])->name('them-cua-hang'); 
Route::post('/admin/quan-ly-cua-hang/them', [App\Http\Controllers\Admin\StoresController::class, 'store'])->name('luu-cua-hang');
Route::get('/admin/quan-ly-cua-hang/cap-nhat-thong-tin-cua-hang/{store}', [App\Http\Controllers\Admin\StoresController::class, 'edit'])->name('cap-nhat-thong-tin-cua-hang');
Route::post('/admin/quan-ly-cua-hang/cap-nhat-thong-tin-cua-hang/{store}', [App\Http\Controllers\Admin\StoresController::class, 'update'])->name('update.stores');
Route::delete('/admin/quan-ly-cua-hang/xoa-cua-hang/{store}', [App\Http\Controllers\Admin\StoresController::class, 'delete'])->name('xoa-cua-hang');

// Route quản lý loại sản phẩm
Route::get('/admin/quan-ly-loai-san-pham/{store_id}', [App\Http\Controllers\Admin\ProductTypesController::class, 'index'])->name('quan-ly-loai-san-pham');
Route::get('/admin/quan-ly-loai-san-pham/them-loai-san-pham/{store_id}', [App\Http\Controllers\Admin\ProductTypesController::class, 'create'])->name('them-loai-san-pham');
Route::post('/admin/quan-ly-loai-san-pham/them-loai-san-pham/{store_id}', [App\Http\Controllers\Admin\ProductTypesController::class, 'store'])->name('luu-loai-san-pham');
Route::get('/admin/quan-ly-loai-san-pham/cap-nhat-loai-san-pham/{type_id}', [App\Http\Controllers\Admin\ProductTypesController::class, 'edit'])->name('cap-nhat-loai-san-pham');
Route::post('/admin/quan-ly-loai-san-pham/cap-nhat-loai-san-pham/{type_id}', [App\Http\Controllers\Admin\ProductTypesController::class, 'update'])->name('update.loai-san-pham');
Route::delete('/admin/quan-ly-loai-san-pham/xoa-loai-san-pham/{type_id}', [App\Http\Controllers\Admin\ProductTypesController::class, 'delete'])->name('xoa-loai-san-pham');

// Route quản lý sản phẩm
Route::get('/admin/quan-ly-san-pham/{store_id}', [App\Http\Controllers\Admin\ProductsController::class, 'index'])->name('quan-ly-san-pham');
Route::post('/admin/quan-ly-san-pham/cap-nhat-trang-thai/{product_id}', [App\Http\Controllers\Admin\ProductsController::class, 'updateStatus'])->name('cap-nhat-trang-thai-san-pham');
Route::get('/admin/quan-ly-san-pham/them-san-pham/{store_id}', [App\Http\Controllers\Admin\ProductsController::class, 'create'])->name('them-san-pham');
Route::post('/admin/quan-ly-san-pham/them-san-pham/{store_id}', [App\Http\Controllers\Admin\ProductsController::class, 'store'])->name('luu-san-pham');
Route::get('/admin/quan-ly-san-pham/cap-nhat-san-pham/{product_id}', [App\Http\Controllers\Admin\ProductsController::class, 'edit'])->name('cap-nhat-san-pham');
Route::post('/admin/quan-ly-san-pham/cap-nhat-san-pham/{product_id}', [App\Http\Controllers\Admin\ProductsController::class, 'update'])->name('update.san-pham');
Route::delete('/admin/quan-ly-san-pham/xoa-san-pham/{product_id}', [App\Http\Controllers\Admin\ProductsController::class, 'delete'])->name('xoa-san-pham');

// Route xóa ảnh sản phẩm
Route::delete('/admin/quan-ly-san-pham/xoa-anh-san-pham/{image_id}', [ProductsController::class, 'deleteImage'])->name('xoa-anh-san-pham');

// Quản lý size theo sản phẩm
Route::get('/admin/quan-ly-san-pham/quan-ly-size/{product_id}', [ProductSizesController::class, 'index'])->name('quan-ly-size');
Route::get('/admin/quan-ly-san-pham/them-size/{product_id}', [ProductSizesController::class, 'create'])->name('them-size');
Route::post('/admin/quan-ly-san-pham/luu-size/{product_id}', [ProductSizesController::class, 'store'])->name('luu-size');
Route::get('/admin/quan-ly-san-pham/cap-nhat-size/{product_id}/{id}', [ProductSizesController::class, 'edit'])->name('cap-nhat-size');
Route::put('/admin/quan-ly-san-pham/cap-nhat-size/{product_id}/{id}', [ProductSizesController::class, 'update'])->name('update-size');
Route::delete('/admin/quan-ly-san-pham/xoa-size/{product_id}/{id}', [ProductSizesController::class, 'delete'])->name('xoa-size');

// Route quản lý đơn mua hàng
Route::get('/admin/quan-ly-don-mua-hang', [ProductOrderController::class, 'index'])->name('quan-ly-don-mua-hang');
Route::post('/admin/quan-ly-don-mua-hang/cap-nhat-trang-thai/{product_order_id}', [ProductOrderController::class, 'updateStatus'])->name('cap-nhat-trang-thai-don-mua-hang');
Route::get('/admin/quan-ly-don-mua-hang/sua-thong-tin/{product_order_id}', [ProductOrderController::class, 'edit'])->name('cap-nhat-don-mua-hang');
Route::delete('/admin/quan-ly-don-mua-hang/xoa/{product_order_id}', [ProductOrderController::class, 'delete'])->name('xoa-don-mua-hang');

// Route quản lý chi tiết đơn mua hàng
Route::get('/admin/quan-ly-don-mua-hang/chi-tiet/sua-thong-tin/{product_order_detail_id}', [ProductOrderDetailController::class, 'index'])->name('cap-nhat-chi-tiet-don-mua-hang');
Route::get('/admin/ajax/product-info/{id}', [ProductOrderDetailController::class, 'getProductInfo']);
Route::post('/admin/quan-ly-don-mua-hang/chi-tiet/cap-nhat/{product_order_detail_id}', [ProductOrderDetailController::class, 'update'])->name('update-chi-tiet-don-mua-hang');
Route::delete('/admin/quan-ly-don-mua-hang/chi-tiet/xoa/{product_order_detail_id}', [ProductOrderDetailController::class, 'delete'])->name('xoa-chi-tiet-don-mua-hang');

// Route quản lý loại tin tức
Route::get('/admin/quan-ly-loai-tin-tuc', [App\Http\Controllers\Admin\NewsTypeController::class, 'index'])->name('quan-ly-loai-tin-tuc');
Route::get('/admin/quan-ly-loai-tin-tuc/them-loai-tin-tuc', [App\Http\Controllers\Admin\NewsTypeController::class, 'create'])->name('them-loai-tin-tuc');
Route::post('/admin/quan-ly-loai-tin-tuc/them-loai-tin-tuc', [App\Http\Controllers\Admin\NewsTypeController::class, 'store'])->name('luu-loai-tin-tuc');
Route::get('/admin/quan-ly-loai-tin-tuc/cap-nhat-loai-tin-tuc/{news_type_id}', [App\Http\Controllers\Admin\NewsTypeController::class, 'edit'])->name('cap-nhat-loai-tin-tuc');
Route::post('/admin/quan-ly-loai-tin-tuc/cap-nhat-loai-tin-tuc/{news_type_id}', [App\Http\Controllers\Admin\NewsTypeController::class, 'update'])->name('update.news_type');
Route::delete('/admin/quan-ly-loai-tin-tuc/xoa-loai-tin-tuc/{news_type_id}', [App\Http\Controllers\Admin\NewsTypeController::class, 'delete'])->name('xoa-loai-tin-tuc');

// Route quản lý tin tức
Route::get('/admin/quan-ly-tin-tuc', [App\Http\Controllers\Admin\NewsController::class, 'index'])->name('quan-ly-tin-tuc');
Route::post('/admin/quan-ly-tin-tuc/cap-nhat-trang-thai-tin-tuc', [App\Http\Controllers\Admin\NewsController::class, 'updateStatus'])->name('cap-nhat-trang-thai-tin-tuc');
Route::get('/admin/quan-ly-tin-tuc/them-tin-tuc', [App\Http\Controllers\Admin\NewsController::class, 'create'])->name('them-tin-tuc');
Route::post('/admin/quan-ly-tin-tuc/them-tin-tuc', [App\Http\Controllers\Admin\NewsController::class, 'store'])->name('luu-tin-tuc');
Route::get('/admin/quan-ly-tin-tuc/cap-nhat-tin-tuc/{news_id}', [App\Http\Controllers\Admin\NewsController::class, 'edit'])->name('cap-nhat-tin-tuc');
Route::post('/admin/quan-ly-tin-tuc/cap-nhat-tin-tuc/{news_id}', [App\Http\Controllers\Admin\NewsController::class, 'update'])->name('update.news');
Route::delete('/admin/quan-ly-tin-tuc/xoa-tin-tuc/{news_id}', [App\Http\Controllers\Admin\NewsController::class, 'delete'])->name('xoa-tin-tuc');

// Route xóa nội dung và ảnh tin tức
Route::delete('/admin/quan-ly-tin-tuc/xoa-noi-dung/{id}', [NewsController::class, 'deleteContent'])->name('xoa-noi-dung');
Route::delete('/admin/quan-ly-tin-tuc/xoa-anh/{id}', [NewsController::class, 'deleteImage'])->name('delete.news.image');

// Royte quản lý thông tin thanh toán
Route::get('/admin/thong-tin-thanh-toan', [PayInfoController::class, 'index'])->name('thong-tin-thanh-toan');
Route::post('/admin/cap-nhat-thong-tin-thanh-toan', [PayInfoController::class, 'update'])->name('cap-nhat-thong-tin-thanh-toan');

// Route thống kê, báo cáo doanh thu
Route::get('/admin/thong-ke-bao-cao', [StatementController::class, 'index'])->name('thong-ke-bao-cao');
Route::get('/admin/thong-ke-bao-cao/xuat-excel-doanh-thu', [StatementController::class, 'exportExcel'])->name('xuat-excel-doanh-thu');


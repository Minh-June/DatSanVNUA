<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PickController;
use App\Http\Controllers\Client\SuccessController;
use App\Http\Controllers\Client\PayController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderDetailController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\TimeController;
use App\Http\Controllers\Admin\YardController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\StatementController;
use App\Http\Controllers\Admin\UserController;

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
Route::get('/thong-tin-tai-khoan/thong-tin-ca-nhan', [AccountController::class, 'editInfor'])->name('thong-tin-ca-nhan');
Route::post('/thong-tin-tai-khoan/cap-nhat-thong-tin-ca-nhan', [AccountController::class, 'updateInfor'])->name('cap-nhat-thong-tin-ca-nhan');
Route::get('/thong-tin-tai-khoan/thay-doi-mat-khau', [AccountController::class, 'editPassword'])->name('thay-doi-mat-khau');
Route::post('/thong-tin-tai-khoan/thay-doi-mat-khau', [AccountController::class, 'updatePassword'])->name('cap-nhat-mat-khau');
Route::post('/thong-tin-tai-khoan/xoa-tai-khoan', [AccountController::class, 'delete'])->name('xoa-tai-khoan');

// Route đặt sân
Route::get('/trang-chu/dat-san/{yard_id}', [PickController::class, 'index'])->name('dat-san');
Route::get('/get-booked-times', [PickController::class, 'getBookedTimes'])->name('getBookedTimes'); // Route lấy khung giờ đã đặt
Route::post('/luu-thong-tin-don-dat-san', [PickController::class, 'store'])->name('luu-thong-tin-don-dat-san');

// Route xác nhận đặt sân
Route::get('/xac-nhan-dat-san', [SuccessController::class, 'index'])->name('xac-nhan-dat-san');
Route::delete('/xoa-don-tam-thoi', [SuccessController::class, 'delete'])->name('xoa-don-tam-thoi');

// Route thanh toán
Route::get('/thanh-toan', [PayController::class, 'index'])->name('thanh-toan');
Route::post('/thanh-toan', [PayController::class, 'store'])->name('pay.upload');

// Route admin
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

// Route quản lý đơn đặt sân
Route::get('/admin/quan-ly-don-dat-san', [OrderController::class, 'index'])->name('quan-ly-don-dat-san');
Route::post('/admin/quan-ly-don-dat-san/cap-nhat-trang-thai-don-dat-san/{order_id}', [OrderController::class, 'updateStatus'])->name('cap-nhat-trang-thai-don-dat-san');
Route::get('/admin/quan-ly-don-dat-san/sua-thong-tin-don-dat-san/{order_id}', [OrderController::class, 'edit'])->name('cap-nhat-don-dat-san');
Route::delete('/admin/quan-ly-don-dat-san/xoa-don-dat-san/{order_id}', [OrderController::class, 'delete'])->name('xoa-don-dat-san');

// Route quản lý chi tiết đơn đặt sân
Route::get('/admin/chi-tiet-don/sua-thong-tin-chi-tiet-don/{order_detail_id}', [OrderDetailController::class, 'index'])->name('cap-nhat-chi-tiet-don');
Route::get('/admin/get-order-detail-times', [OrderDetailController::class, 'getTimesByYardAndDate'])->name('getTimesByYardAndDate');
Route::post('/admin/chi-tiet-don/cap-nhat-thong-tin-chi-tiet-don/{order_detail_id}', [OrderDetailController::class, 'update'])->name('update.order_detail');
Route::delete('/admin/chi-tiet-don/xĂ³a-thong-tin-chi-tiet-don/{order_detail_id}', [OrderDetailController::class, 'delete'])->name('xoa-chi-tiet-don');

// Route thống kê, báo cáo doanh thu
Route::get('/admin/thong-ke-bao-cao', [StatementController::class, 'index'])->name('thong-ke-bao-cao');
Route::get('/admin/thong-ke-bao-cao/xuat-excel-doanh-thu', [App\Http\Controllers\Admin\StatementController::class, 'exportExcel'])->name('xuat-excel-doanh-thu');


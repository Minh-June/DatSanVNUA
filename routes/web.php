<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\PayController;
use App\Http\Controllers\Client\PickController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ImageYardController;
use App\Http\Controllers\Admin\TimeYardController;
use App\Http\Controllers\Admin\YardController;

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

// Route đăng ký
Route::get('/dang-ky', [RegisterController::class, 'showRegistrationForm'])->name('dang-ky');
Route::post('/dang-ky', [RegisterController::class, 'register'])->name('dang-ky');

// Route đăng nhập
Route::get('/dang-nhap', [LoginController::class, 'showLoginForm'])->name('dang-nhap');
Route::post('/dang-nhap', [LoginController::class, 'login']);

// Route đăng xuất
Route::post('/dang-xuat', [LoginController::class, 'logout'])->name('dang-xuat');

// Route quản lý tài khoản người dùng
Route::get('/thong-tin-tai-khoan', [UserController::class, 'index'])->name('thong-tin-tai-khoan');
Route::get('/thong-tin-tai-khoan/thong-tin-ca-nhan', [UserController::class, 'edit'])->name('thong-tin-ca-nhan');
Route::post('/thong-tin-tai-khoan/cap-nhat-thong-tin-ca-nhan', [UserController::class, 'update'])->name('cap-nhat-thong-tin');
Route::get('/thong-tin-tai-khoan/thay-doi-mat-khau', [UserController::class, 'changePassword'])->name('thay-doi-mat-khau');
Route::post('/thong-tin-tai-khoan/thay-doi-mat-khau', [UserController::class, 'updatePassword'])->name('thay-doi-mat-khau');

// Route đặt sân
Route::get('/trang-chu/dat-san/{id}', [PickController::class, 'index'])->name('dat-san');
Route::get('/get-booked-times', [PickController::class, 'getBookedTimesForDate']);

// Route xác nhận đặt sân
Route::get('/xac-nhan-dat-san', [OrderController::class, 'index']);
Route::post('/xac-nhan-dat-san', [OrderController::class, 'store'])->name('xac-nhan-dat-san');

// Route thanh toán trước
Route::get('/thanh-toan', [PayController::class, 'index'])->name('thanh-toan');
Route::post('/thanh-toan', [PayController::class, 'store'])->name('pay.upload');

// Route admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin');

// Route quản lý đơn đặt
Route::get('/admin/quan-ly-khach-hang', [CustomerController::class, 'index'])->name('quan-ly-khach-hang');
Route::post('/admin/quan-ly-khach-hang/cap-nhat-trang-thai/{id}', [CustomerController::class, 'updateStatus'])->name('orders.updateStatus');
Route::get('/admin/quan-ly-khach-hang/them-khach-hang', [CustomerController::class, 'create'])->name('them-khach-hang');
Route::post('/admin/quan-ly-khach-hang/them-khach-hang', [CustomerController::class, 'store'])->name('store.order');
Route::get('/admin/quan-ly-khach-hang/sua-thong-tin-don/{id}', [CustomerController::class, 'edit'])->name('orders.edit');
Route::post('/admin/quan-ly-khach-hang/cap-nhat-thong-tin-don/{id}', [CustomerController::class, 'update'])->name('orders.update');
Route::delete('/admin/quan-ly-khach-hang/xoa-don/{id}', [CustomerController::class, 'delete'])->name('orders.delete');

// Route quản lý sân
Route::get('/admin/quan-ly-san', [YardController::class, 'index'])->name('quan-ly-san');
Route::get('/admin/quan-ly-san/them-san', [YardController::class, 'create'])->name('them-san'); // Route để hiển thị form thêm sân
Route::post('/admin/quan-ly-san/them-san', [YardController::class, 'store']); // Route để lưu sân mới
Route::get('/admin/quan-ly-san/cap-nhat-san/{san_id}', [YardController::class, 'edit'])->name('cap-nhat-san');
Route::post('/admin/quan-ly-san/cap-nhat-san/{san_id}', [YardController::class, 'update'])->name('yards.update');
Route::delete('/admin/quan-ly-san/xoa-san/{san_id}', [YardController::class, 'delete'])->name('delete-yard');

// Route quản lý thời gian sân
Route::get('/admin/quan-ly-thoi-gian-san', [TimeYardController::class, 'index'])->name('quan-ly-thoi-gian-san');
Route::post('/admin/quan-ly-thoi-gian-san', [TimeYardController::class, 'search'])->name('search.time_slots');
Route::get('/admin/quan-ly-thoi-gian-san/them-thoi-gian-san', [TimeYardController::class, 'create'])->name('them-thoi-gian-san');
Route::post('/admin/quan-ly-thoi-gian-san/them-thoi-gian-san', [TimeYardController::class, 'store'])->name('store.time_slot');
Route::get('/admin/quan-ly-thoi-gian-san/cap-nhat-thoi-gian-san/{id}', [TimeYardController::class, 'edit'])->name('cap-nhat-thoi-gian-san');
Route::post('/admin/quan-ly-thoi-gian-san/cap-nhat-thoi-gian-san/{id}', [TimeYardController::class, 'update'])->name('cap-nhat-thoi-gian-san');
Route::delete('/admin/quan-ly-thoi-gian-san/xoa-thoi-gian-san/{id}', [TimeYardController::class, 'delete'])->name('delete-time-slot');

// Route quản lý hình ảnh sân
Route::get('/admin/quan-ly-hinh-anh-san', [ImageYardController::class, 'index'])->name('quan-ly-hinh-anh-san');
Route::get('/admin/quan-ly-hinh-anh-san/them-hinh-anh-san', [ImageYardController::class, 'create'])->name('them-hinh-anh-san');
Route::post('/admin/quan-ly-hinh-anh-san/them-hinh-anh-san', [ImageYardController::class, 'store'])->name('store.hinh-anh-san');
Route::get('/admin/quan-ly-hinh-anh-san/sua-hinh-anh-san/{image_id}', [ImageYardController::class, 'edit'])->name('sua-hinh-anh-san');
Route::post('/admin/quan-ly-hinh-anh-san/sua-hinh-anh-san/{image_id}', [ImageYardController::class, 'update'])->name('sua-hinh-anh-san');
Route::delete('/admin/quan-ly-hinh-anh-san/xoa-hinh-anh-san/{image_id}', [ImageYardController::class, 'destroy'])->name('xoa-hinh-anh-san');

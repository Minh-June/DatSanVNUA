<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Image;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View composer truyền biến cho layout client
        View::composer('layouts.client.client', function ($view) {
            // Lấy đơn hàng từ session
            $orders = Session::get('orders', []);

            // Lấy mảng yard_id duy nhất từ đơn hàng
            $yardIds = collect($orders)->pluck('yard_id')->unique()->toArray();

            // Lấy ảnh đầu tiên của từng sân trong đơn hàng
            $yardFirstImages = [];

            if (!empty($yardIds)) {
                $images = Image::whereIn('yard_id', $yardIds)
                    ->orderBy('yard_id')
                    ->orderBy('image_id')
                    ->get()
                    ->groupBy('yard_id');

                foreach ($images as $yardId => $imgs) {
                    $yardFirstImages[$yardId] = asset('storage/' . $imgs->first()->image);
                }
            }

            // Truyền biến cho view
            $view->with('yardFirstImages', $yardFirstImages);
        });
    }
}

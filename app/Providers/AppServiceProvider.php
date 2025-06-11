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
        // View composer truyá»n biáº¿n cho layout client
        View::composer('layouts.client.client', function ($view) {
            // Láº¥y Ä‘Æ¡n hĂ ng tá»« session
            $orders = Session::get('orders', []);

            // Láº¥y máº£ng yard_id duy nháº¥t tá»« Ä‘Æ¡n hĂ ng
            $yardIds = collect($orders)->pluck('yard_id')->unique()->toArray();

            // Láº¥y áº£nh Ä‘áº§u tiĂªn cá»§a tá»«ng sĂ¢n trong Ä‘Æ¡n hĂ ng
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

            // Truyá»n biáº¿n cho view
            $view->with('yardFirstImages', $yardFirstImages);
        });
    }
}

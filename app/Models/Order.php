<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders'; // TĂªn báº£ng
    protected $primaryKey = 'order_id'; // KhĂ³a chĂ­nh
    public $timestamps = false; // KhĂ´ng sá»­ dá»¥ng timestamps

    // CĂ¡c tráº¡ng thĂ¡i Ä‘Æ¡n hĂ ng
    const STATUS_PENDING   = 'chá» xĂ¡c nháº­n';
    const STATUS_CONFIRMED = 'Ä‘Ă£ xĂ¡c nháº­n';
    const STATUS_CANCELLED = 'Ä‘Ă£ há»§y';

    protected $casts = [
        'status' => 'integer',
    ];

    protected $fillable = [
        'date', // NgĂ y táº¡o Ä‘Æ¡n, kiá»ƒu dá»¯ liá»‡u datetime
        'user_id',
        'name', // TĂªn ngÆ°á»i Ä‘áº·t
        'phone',   // Sá»‘ Ä‘iá»‡n thoáº¡i ngÆ°á»i Ä‘áº·t
        'image', // áº¢nh thanh toĂ¡n ngÆ°á»i Ä‘áº·t up lĂªn
        'status', // Tráº¡ng thĂ¡i Ä‘Æ¡n (máº·c Ä‘á»‹nh lĂ  0: chá» xĂ¡c nháº­n, 1: Ä‘Ă£ xĂ¡c nháº­n, 2: Ä‘Ă£ há»§y)
    ]; // CĂ¡c cá»™t cĂ³ thá»ƒ gĂ¡n

    // Quan há»‡: Má»™t Ä‘Æ¡n hĂ ng thuá»™c vá» má»™t ngÆ°á»i dĂ¹ng
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Quan há»‡: Má»™t Ä‘Æ¡n hĂ ng cĂ³ nhiá»u chi tiáº¿t Ä‘Æ¡n hĂ ng
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
}

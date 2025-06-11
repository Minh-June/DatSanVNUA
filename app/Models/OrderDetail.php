<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details'; // TĂªn báº£ng 
    protected $primaryKey = 'order_detail_id'; // KhĂ³a chĂ­nh
    public $timestamps = false; // KhĂ´ng sá»­ dá»¥ng timestamps

    protected $fillable = [
        'order_id',
        'date', // NgĂ y Ä‘áº·t sĂ¢n
        'time', // Khung giá» cá»§a sĂ¢n
        'yard_id',
        'price', // GiĂ¡ cá»§a khung giá»
        'notes', // Ghi chĂº cá»§a ngÆ°á»i dĂ¹ng
    ]; // CĂ¡c cá»™t cĂ³ thá»ƒ gĂ¡n

    // Quan há»‡: Má»™t chi tiáº¿t Ä‘Æ¡n hĂ ng thuá»™c vá» má»™t Ä‘Æ¡n hĂ ng
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // Quan há»‡: Má»™t chi tiáº¿t Ä‘Æ¡n hĂ ng thuá»™c vá» má»™t sĂ¢n
    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }
}

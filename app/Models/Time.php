<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $table = 'times'; // TĂªn báº£ng
    protected $primaryKey = 'time_id'; // KhĂ³a chĂ­nh
    public $timestamps = false; // KhĂ´ng sá»­ dá»¥ng timestamps

    protected $fillable = [
        'time_id',
        'yard_id', 
        'date', // NgĂ y cá»§a khung giá» vĂ  giĂ¡ sĂ¢n theo yard_id
        'time', // Khung giá» cá»§a sĂ¢n
        'price' // GiĂ¡ cá»§a khung giá»
    ]; // CĂ¡c cá»™t cĂ³ thá»ƒ gĂ¡n

    // Quan há»‡: Má»™t khung giá» thuá»™c vá» má»™t sĂ¢n
    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images'; // TĂªn báº£ng
    protected $primaryKey = 'image_id'; // KhĂ³a chĂ­nh
    public $timestamps = false; // KhĂ´ng sá»­ dá»¥ng timestamps

    protected $fillable = [
        'image_id',
        'yard_id', 
        'image' // áº¢nh sĂ¢n
    ]; // CĂ¡c cá»™t cĂ³ thá»ƒ gĂ¡n

    // Quan há»‡: Má»™t áº£nh thuá»™c vá» má»™t sĂ¢n
    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }

    // Tráº£ vá» Ä‘Æ°á»ng dáº«n áº£nh
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image); // Láº¥y Ä‘Æ°á»ng dáº«n Ä‘áº§y Ä‘á»§ tá»« thÆ° má»¥c public
    }
}

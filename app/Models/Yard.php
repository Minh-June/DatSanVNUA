<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yard extends Model
{
    use HasFactory;

    protected $table = 'yards'; // TĂªn báº£ng trong cÆ¡ sá»Ÿ dá»¯ liá»‡u
    protected $primaryKey = 'yard_id'; // KhĂ³a chĂ­nh
    public $timestamps = false; // KhĂ´ng sá»­ dá»¥ng timestamps (created_at, updated_at)

    protected $fillable = [
        'yard_id',
        'type_id',
        'name' // TĂªn sĂ¢n
    ]; // CĂ¡c cá»™t cĂ³ thá»ƒ gĂ¡n

    // Quan há»‡: Má»™t sĂ¢n cĂ³ nhiá»u áº£nh
    public function images()
    {
        return $this->hasMany(Image::class, 'yard_id', 'yard_id');
    }

    // Quan há»‡: Má»™t sĂ¢n cĂ³ nhiá»u Ä‘Æ¡n Ä‘áº·t
    public function orders()
    {
        return $this->hasMany(Order::class, 'yard_id', 'yard_id');
    }

    // Quan há»‡: Má»™t sĂ¢n cĂ³ nhiá»u khung giá»
    public function times()
    {
        return $this->hasMany(Time::class, 'yard_id', 'yard_id');
    }

    // Quan há»‡: Má»™t sĂ¢n thuá»™c má»™t loáº¡i sĂ¢n
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'type_id'); // Sá»­a láº¡i tham sá»‘ khĂ³a quan há»‡
    }

}

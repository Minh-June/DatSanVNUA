<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types'; // TĂªn báº£ng
    protected $primaryKey = 'type_id'; // KhĂ³a chĂ­nh
    public $timestamps = false; // KhĂ´ng sá»­ dá»¥ng timestamps

    protected $fillable = [
        'type_id',
        'name' // TĂªn loáº¡i sĂ¢n
    ]; // CĂ¡c cá»™t cĂ³ thá»ƒ gĂ¡n

    // Quan há»‡: Má»™t loáº¡i sĂ¢n cĂ³ nhiá»u sĂ¢n
    public function yards()
    {
        return $this->hasMany(Yard::class, 'type_id', 'type_id'); // Sá»­a láº¡i tham sá»‘ khĂ³a quan há»‡
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yard extends Model
{
    use HasFactory;

    protected $table = 'yards';
    protected $primaryKey = 'yard_id';
    public $timestamps = false;

    protected $fillable = [
        'type_id',  // Loại sân
        'name', // Tên sân
        'status',
        'user_id', // Chủ sân
    ];

    public function images()
    {
        return $this->hasMany(Image::class, 'yard_id', 'yard_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'yard_id', 'yard_id');
    }

    public function times()
    {
        return $this->hasMany(Time::class, 'yard_id', 'yard_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
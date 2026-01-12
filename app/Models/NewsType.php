<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsType extends Model
{
    use HasFactory;

    protected $table = 'news_types';
    protected $primaryKey = 'news_type_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', // ID người quản lý loại tin tức
        'name', // Tên loại tin tức
    ];

    // Quan hệ: loại tin có nhiều bài viết
    public function news()
    {
        return $this->hasMany(News::class, 'news_type_id', 'news_type_id');
    }

    // Quan hệ: loại tin được quản lý bởi người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

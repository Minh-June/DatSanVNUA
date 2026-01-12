<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $primaryKey = 'news_id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'user_id', // ID người đăng bài
        'news_type_id',
        'status',   // 0 = hiển thị, 1 = ẩn
        'post_at',
    ];

    // Quan hệ: bài viết thuộc về người đăng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Quan hệ: bài viết thuộc loại tin
    public function type()
    {
        return $this->belongsTo(NewsType::class, 'news_type_id', 'news_type_id');
    }

    // Quan hệ: bài viết có nhiều nội dung
    public function contents()
    {
        return $this->hasMany(NewsContent::class, 'news_id', 'news_id');
    }
}

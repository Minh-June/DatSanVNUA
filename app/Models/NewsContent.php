<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsContent extends Model
{
    use HasFactory;

    protected $table = 'news_contents';
    protected $primaryKey = 'news_content_id';
    public $timestamps = false;

    protected $fillable = [
        'news_id',
        'content',
        'image',
        'note',
    ];

    // Quan hệ: nội dung thuộc về bài viết
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'news_id');
    }
}

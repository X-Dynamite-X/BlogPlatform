<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'content', 'user_id', 'category_id',
    ];

    // العلاقة مع المستخدم: المقال ينتمي إلى كاتب (مستخدم)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع التصنيف: المقال ينتمي إلى تصنيف
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع التعليقات: المقال يحتوي على العديد من التعليقات
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // العلاقة مع الوسوم: المقال يمكن أن يحتوي على العديد من الوسوم
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

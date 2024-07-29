<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'content', 'user_id', 'post_id',
    ];

    // العلاقة مع المستخدم: التعليق ينتمي إلى مستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المقال: التعليق ينتمي إلى مقال
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

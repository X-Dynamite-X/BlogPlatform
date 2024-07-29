<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    // العلاقة مع المقالات: الوسم يمكن أن يرتبط بالعديد من المقالات
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}


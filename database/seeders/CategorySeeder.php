<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentCategory;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $Categories = [
            'Technology' ,
            'Health',
            'Lifestyle' ,
            'Business',
            'Education',
        ];

        foreach ($Categories as $Categorie ) {
            // إنشاء الفئة الرئيسية
            Category::firstOrCreate([
                'name' => $Categorie,
            ]);
        }
    }
}

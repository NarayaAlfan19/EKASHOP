<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Flower;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Buket Bunga',
            'Buket Non-Bunga',
            'Bunga Meja & Rangkaian',
            'Wisuda & Spesial',
            'Dekorasi & Sewa',
        ];

        $keptIds = [];
        foreach ($categories as $name) {
            $cat = Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $keptIds[] = $cat->id;
        }

        $defaultCategoryId = $keptIds[0];
        Flower::whereNotIn('category_id', $keptIds)->update(['category_id' => $defaultCategoryId]);
        Category::whereNotIn('id', $keptIds)->delete();
    }
}
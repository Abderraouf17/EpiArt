<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            [
                'name' => 'التوابل الأساسية',
                'slug' => 'basic-spices',
                'image_url' => '/images/basic.jpeg',
                'products' => [
                    ['name' => 'الفلفل الأسود', 'description' => 'فلفل أسود عالي الجودة من الهند', 'price' => 450],
                    ['name' => 'الملح', 'description' => 'ملح طبيعي نقي', 'price' => 150],
                    ['name' => 'الزنجبيل', 'description' => 'زنجبيل طازج مجفف', 'price' => 350],
                ]
            ],
            [
                'name' => 'الحبوب والبقوليات',
                'slug' => 'cereals',
                'image_url' => '/images/cereals.jpg',
                'products' => [
                    ['name' => 'الأرز الأبيض', 'description' => 'أرز أبيض طويل الحبة', 'price' => 1200],
                    ['name' => 'العدس الأحمر', 'description' => 'عدس أحمر صافي', 'price' => 800],
                    ['name' => 'الحمص', 'description' => 'حمص كبير الحبة', 'price' => 900],
                ]
            ],
            [
                'name' => 'الخلطات المتنوعة',
                'slug' => 'mix',
                'image_url' => '/images/mix.jpg',
                'products' => [
                    ['name' => 'خليط الطحين', 'description' => 'خليط طحين متعدد الحبوب', 'price' => 600],
                    ['name' => 'خليط التوابل', 'description' => 'خليط توابل متوازن', 'price' => 700],
                ]
            ],
            [
                'name' => 'القهوة والشاي',
                'slug' => 'coffee-tea',
                'image_url' => '/images/coffeetea.jpg',
                'products' => [
                    ['name' => 'القهوة العربية', 'description' => 'قهوة عربية أصلية', 'price' => 1500],
                    ['name' => 'شاي أسود', 'description' => 'شاي أسود من الهند', 'price' => 600],
                    ['name' => 'شاي أخضر', 'description' => 'شاي أخضر صيني', 'price' => 700],
                ]
            ],
            [
                'name' => 'الزيوت',
                'slug' => 'oils',
                'image_url' => '/images/oils.jpg',
                'products' => [
                    ['name' => 'زيت الزيتون', 'description' => 'زيت زيتون بكر', 'price' => 2500],
                    ['name' => 'زيت جوز الهند', 'description' => 'زيت جوز هند عضوي', 'price' => 1800],
                ]
            ],
        ];

        foreach ($categories as $catIndex => $cat) {
            $category = Category::create([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'image_url' => $cat['image_url'],
            ]);

            foreach ($cat['products'] as $prodIndex => $prod) {
                // Mark first product of each category as featured, plus some special ones
                $isFeatured = ($prodIndex === 0) || ($catIndex === 0 && $prodIndex === 0) || ($catIndex === 4 && $prodIndex === 0);
                
                Product::create([
                    'name' => $prod['name'],
                    'slug' => \Illuminate\Support\Str::slug($prod['name']),
                    'description' => $prod['description'],
                    'price' => $prod['price'],
                    'category_id' => $category->id,
                    'is_featured' => $isFeatured,
                ]);
            }
        }
    }
}

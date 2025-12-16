<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories with products
        $categories = [
            [
                'name' => 'التوابل الأساسية',
                'slug' => 'basic-spices',
                'type' => 'spice',
                'image_url' => '/images/basic.jpeg',
                'products' => [
                    ['name' => 'الفلفل الأسود', 'description' => 'فلفل أسود عالي الجودة من الهند', 'price' => 450, 'is_featured' => true, 'is_new' => true, 'image' => 'https://images.unsplash.com/photo-1599909533601-fc89e4a998c4?w=500&h=500&fit=crop'],
                    ['name' => 'الملح', 'description' => 'ملح طبيعي نقي', 'price' => 150, 'is_featured' => false, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1518110925495-5fe2fda0442c?w=500&h=500&fit=crop'],
                    ['name' => 'الزنجبيل', 'description' => 'زنجبيل طازج مجفف', 'price' => 350, 'is_featured' => false, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=500&h=500&fit=crop'],
                ]
            ],
            [
                'name' => 'الحبوب والبقوليات',
                'slug' => 'cereals',
                'type' => 'spice',
                'image_url' => '/images/cereals.jpg',
                'products' => [
                    ['name' => 'الأرز الأبيض', 'description' => 'أرز أبيض طويل الحبة', 'price' => 1200, 'is_featured' => true, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&h=500&fit=crop'],
                    ['name' => 'العدس الأحمر', 'description' => 'عدس أحمر صافي', 'price' => 800, 'is_featured' => false, 'is_new' => true, 'image' => 'https://images.unsplash.com/photo-1585996093172-1f86d8ee6f3c?w=500&h=500&fit=crop'],
                    ['name' => 'الحمص', 'description' => 'حمص كبير الحبة', 'price' => 900, 'is_featured' => false, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1515543904323-f6c11ddae4da?w=500&h=500&fit=crop'],
                ]
            ],
            [
                'name' => 'الخلطات المتنوعة',
                'slug' => 'mix',
                'type' => 'spice',
                'image_url' => '/images/mix.jpg',
                'products' => [
                    ['name' => 'خليط الطحين', 'description' => 'خليط طحين متعدد الحبوب', 'price' => 600, 'is_featured' => false, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=500&h=500&fit=crop'],
                    ['name' => 'خليط التوابل', 'description' => 'خليط توابل متوازن', 'price' => 700, 'is_featured' => true, 'is_new' => true, 'image' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=500&h=500&fit=crop'],
                ]
            ],
            [
                'name' => 'القهوة والشاي',
                'slug' => 'coffee-tea',
                'type' => 'spice',
                'image_url' => '/images/coffeetea.jpg',
                'products' => [
                    ['name' => 'القهوة العربية', 'description' => 'قهوة عربية أصلية', 'price' => 1500, 'is_featured' => true, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&h=500&fit=crop'],
                    ['name' => 'شاي أسود', 'description' => 'شاي أسود من الهند', 'price' => 600, 'is_featured' => false, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1594631252845-29fc4cc8cde9?w=500&h=500&fit=crop'],
                    ['name' => 'شاي أخضر', 'description' => 'شاي أخضر صيني', 'price' => 700, 'is_featured' => false, 'is_new' => true, 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&h=500&fit=crop'],
                ]
            ],
            [
                'name' => 'الزيوت',
                'slug' => 'oils',
                'type' => 'spice',
                'image_url' => '/images/oils.jpg',
                'products' => [
                    ['name' => 'زيت الزيتون', 'description' => 'زيت زيتون بكر', 'price' => 2500, 'is_featured' => true, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500&h=500&fit=crop'],
                    ['name' => 'زيت جوز الهند', 'description' => 'زيت جوز هند عضوي', 'price' => 1800, 'is_featured' => false, 'is_new' => false, 'image' => 'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=500&h=500&fit=crop'],
                ]
            ],
        ];

        foreach ($categories as $catData) {
            $category = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'slug' => $catData['slug'],
                    'type' => $catData['type'],
                    'image_url' => $catData['image_url'],
                ]
            );

            foreach ($catData['products'] as $prodData) {
                $product = Product::updateOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($prodData['name'])],
                    [
                        'name' => $prodData['name'],
                        'slug' => \Illuminate\Support\Str::slug($prodData['name']),
                        'type' => 'spice',
                        'description' => $prodData['description'],
                        'price' => $prodData['price'],
                        'stock' => rand(10, 100),
                        'category_id' => $category->id,
                        'is_featured' => $prodData['is_featured'],
                        'is_new' => $prodData['is_new'],
                    ]
                );

                // Create product image
                ProductImage::updateOrCreate(
                    ['product_id' => $product->id, 'display_order' => 1],
                    [
                        'image_url' => $prodData['image'],
                        'display_order' => 1,
                    ]
                );
            }
        }
    }
}

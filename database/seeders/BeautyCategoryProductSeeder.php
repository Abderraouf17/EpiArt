<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

class BeautyCategoryProductSeeder extends Seeder
{
    public function run()
    {
        // Beauty Categories
        $categories = [
            [
                'name' => 'Skincare',
                'slug' => 'skincare',
                'type' => 'beauty',
                'description' => 'Natural skincare products for radiant skin',
                'image_url' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&h=1200&fit=crop',
                'display_order' => 6,
            ],
            [
                'name' => 'Haircare',
                'slug' => 'haircare',
                'type' => 'beauty',
                'description' => 'Organic haircare for healthy, beautiful hair',
                'image_url' => 'https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=800&h=1200&fit=crop',
                'display_order' => 7,
            ],
            [
                'name' => 'Aromatherapy',
                'slug' => 'aromatherapy',
                'type' => 'beauty',
                'description' => 'Essential oils and aromatherapy products',
                'image_url' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&h=1200&fit=crop',
                'display_order' => 8,
            ],
            [
                'name' => 'Cosmetics',
                'slug' => 'cosmetics',
                'type' => 'beauty',
                'description' => 'Natural and organic cosmetics',
                'image_url' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&h=1200&fit=crop',
                'display_order' => 9,
            ],
            [
                'name' => 'Wellness',
                'slug' => 'wellness',
                'type' => 'beauty',
                'description' => 'Wellness and self-care products',
                'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=1200&fit=crop',
                'display_order' => 10,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            // Create products for each category
            $this->createProductsForCategory($category);
        }
    }

    private function createProductsForCategory($category)
    {
        $products = [];

        switch ($category->slug) {
            case 'skincare':
                $products = [
                    [
                        'name' => 'Argan Oil Face Serum',
                        'description' => 'Pure Moroccan argan oil for deep hydration and anti-aging benefits',
                        'price' => 3500,
                        'is_featured' => true,
                        'is_new' => true,
                        'display_order' => 1,
                        'image_url' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Rose Water Toner',
                        'description' => 'Natural rose water toner for refreshing and balancing skin',
                        'price' => 1800,
                        'is_featured' => true,
                        'is_new' => false,
                        'display_order' => 2,
                        'image_url' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Aloe Vera Gel',
                        'description' => 'Pure aloe vera gel for soothing and moisturizing',
                        'price' => 1500,
                        'is_featured' => false,
                        'is_new' => true,
                        'display_order' => 3,
                        'image_url' => 'https://images.unsplash.com/photo-1556228852-80a5e2c3e7d9?w=500&h=500&fit=crop',
                    ],
                ];
                break;

            case 'haircare':
                $products = [
                    [
                        'name' => 'Coconut Oil Hair Mask',
                        'description' => 'Deep conditioning hair mask with pure coconut oil',
                        'price' => 2500,
                        'is_featured' => true,
                        'is_new' => true,
                        'display_order' => 4,
                        'image_url' => 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Herbal Shampoo',
                        'description' => 'Natural herbal shampoo for healthy hair growth',
                        'price' => 2200,
                        'is_featured' => true,
                        'is_new' => false,
                        'display_order' => 5,
                        'image_url' => 'https://images.unsplash.com/photo-1571875257727-256c39da42af?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Argan Hair Oil',
                        'description' => 'Lightweight argan oil for shine and smoothness',
                        'price' => 3000,
                        'is_featured' => false,
                        'is_new' => false,
                        'display_order' => 6,
                        'image_url' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=500&h=500&fit=crop',
                    ],
                ];
                break;

            case 'aromatherapy':
                $products = [
                    [
                        'name' => 'Lavender Essential Oil',
                        'description' => 'Pure lavender oil for relaxation and better sleep',
                        'price' => 2800,
                        'is_featured' => true,
                        'is_new' => false,
                        'display_order' => 7,
                        'image_url' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Eucalyptus Oil',
                        'description' => 'Refreshing eucalyptus oil for respiratory wellness',
                        'price' => 2500,
                        'is_featured' => true,
                        'is_new' => false,
                        'display_order' => 8,
                        'image_url' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Peppermint Essential Oil',
                        'description' => 'Energizing peppermint oil for focus and clarity',
                        'price' => 2600,
                        'is_featured' => false,
                        'is_new' => false,
                        'display_order' => 9,
                        'image_url' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=500&h=500&fit=crop',
                    ],
                ];
                break;

            case 'cosmetics':
                $products = [
                    [
                        'name' => 'Natural Lip Balm',
                        'description' => 'Moisturizing lip balm with beeswax and shea butter',
                        'price' => 800,
                        'is_featured' => false,
                        'is_new' => false,
                        'display_order' => 10,
                        'image_url' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Mineral Foundation',
                        'description' => 'Natural mineral foundation for flawless coverage',
                        'price' => 4500,
                        'is_featured' => false,
                        'is_new' => false,
                        'display_order' => 11,
                        'image_url' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&h=500&fit=crop',
                    ],
                ];
                break;

            case 'wellness':
                $products = [
                    [
                        'name' => 'Bath Salts Collection',
                        'description' => 'Relaxing bath salts with essential oils',
                        'price' => 3200,
                        'is_featured' => false,
                        'is_new' => false,
                        'display_order' => 12,
                        'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=500&h=500&fit=crop',
                    ],
                    [
                        'name' => 'Herbal Tea Blend',
                        'description' => 'Calming herbal tea for wellness and relaxation',
                        'price' => 1800,
                        'is_featured' => false,
                        'is_new' => false,
                        'display_order' => 13,
                        'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=500&h=500&fit=crop',
                    ],
                ];
                break;
        }

        foreach ($products as $productData) {
            $imageUrl = $productData['image_url'];
            unset($productData['image_url']);
            unset($productData['display_order']); // Remove display_order from product data

            $product = Product::updateOrCreate(
                ['slug' => \Str::slug($productData['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'slug' => \Str::slug($productData['name']),
                    'type' => 'beauty',
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => rand(10, 100),
                    'is_featured' => $productData['is_featured'],
                    'is_new' => $productData['is_new'] ?? false,
                ]
            );

            // Create product image if it doesn't exist
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'display_order' => 1],
                [
                    'image_url' => $imageUrl,
                    'display_order' => 1,
                ]
            );
        }
    }
}

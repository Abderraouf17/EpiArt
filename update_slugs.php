<?php

// Run this file to update product slugs
// php update_slugs.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::whereNull('slug')->orWhere('slug', '')->get();

echo "Found {$products->count()} products without slugs\n";

foreach ($products as $product) {
    $product->slug = Product::generateSlug($product->name);
    $product->save();
    echo "Updated: {$product->name} -> {$product->slug}\n";
}

echo "\nDone! All products now have slugs.\n";

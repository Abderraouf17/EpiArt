<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'slug',
        'description',
        'image',
        'image_url',
        'featured_product_id',
        'display_order',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function featuredProduct()
    {
        return $this->belongsTo(Product::class, 'featured_product_id');
    }
}

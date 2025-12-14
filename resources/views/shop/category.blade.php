@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 2.5rem; font-weight: bold; margin-bottom: 2rem; color: #1f2937;">{{ $category->name }}</h1>

    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 2rem;">
        <!-- Filters -->
        <div>
            <h3 style="font-weight: bold; margin-bottom: 1.5rem;">الفئات</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="/shop" style="padding: 0.75rem; color: #6b7280; text-decoration: none; border-radius: 6px; border: 1px solid #e5e7eb;">الكل</a>
                @foreach($categories as $cat)
                    <a href="/shop/category/{{ $cat->id }}" style="padding: 0.75rem; color: #6b7280; text-decoration: none; border-radius: 6px; border: 1px solid #e5e7eb;">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>

        <!-- Products -->
        <div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @foreach($products as $product)
                    <a href="/shop/product/{{ $product->slug }}" style="text-decoration: none; color: inherit;">
                        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s;" class="product-card">
                            <div style="position: relative; aspect-ratio: 1; overflow: hidden;">
                                @if($product->images->first())
                                    <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #8B3A3A 0%, #722F37 100%);"></div>
                                @endif
                            </div>
                            <div style="padding: 1rem;">
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1rem;">{{ $product->name }}</h3>
                                <p style="color: #8B3A3A; font-size: 1.25rem; font-weight: bold;">{{ number_format($product->price, 0) }} DA</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div style="text-align: center; padding: 2rem;">
                    <p style="color: #6b7280; font-size: 1.125rem;">لا توجد منتجات في هذه الفئة</p>
                </div>
            @endif

            <div style="margin-top: 2rem;">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .product-card img {
        transition: transform 0.3s;
    }
    .product-card:hover img {
        transform: scale(1.1);
    }
    .product-card:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
        transform: translateY(-4px);
    }
</style>
@endsection

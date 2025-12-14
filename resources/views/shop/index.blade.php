@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 2.5rem; font-weight: bold; margin-bottom: 2rem; text-align: center; color: #1f2937;">متجر التوابل</h1>

    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 2rem;">
        <!-- Filters -->
        <div>
            <h3 style="font-weight: bold; margin-bottom: 1.5rem;">الفئات</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="/shop" style="padding: 0.75rem; color: #6b7280; text-decoration: none; border-radius: 6px; border: 1px solid #e5e7eb;">الكل</a>
                @foreach($categories as $cat)
                    <a href="/shop/category/{{ $cat->id }}" style="padding: 0.75rem; color: #6b7280; text-decoration: none; border-radius: 6px; border: 1px solid #e5e7eb; transition: all 0.3s;">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>

        <!-- Products -->
        <div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @foreach($products as $product)
                    <a href="/shop/product/{{ $product->slug }}" style="text-decoration: none; color: inherit; transition: all 0.3s;">
                        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s;" class="product-card group relative">
                            <div style="position: relative; aspect-ratio: 1; overflow: hidden;">
                                @if($product->images->first())
                                    <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                                @else
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #8B3A3A 0%, #722F37 100%);"></div>
                                @endif
                            </div>
                            <div style="padding: 1rem;">
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</h3>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                    <p style="color: #8B3A3A; font-size: 1.1rem; font-weight: bold;">{{ number_format($product->price, 0) }} DA</p>
                                    
                                    <div style="display: flex; gap: 0.5rem; z-index: 20; position: relative;">
                                        <button onclick="event.preventDefault(); event.stopPropagation(); addToWishlist({{ $product->id }})" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition" title="Add to Wishlist">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        </button>
                                        @if($product->variations->count() > 0)
                                            <button onclick="event.preventDefault(); event.stopPropagation(); window.location.href='/shop/product/{{ $product->slug }}'" class="w-8 h-8 rounded-full bg-[#8B3A3A] flex items-center justify-center text-white hover:bg-[#722F37] transition shadow-md" title="Select Options">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </button>
                                        @else
                                            <button onclick='event.preventDefault(); event.stopPropagation(); quickAddToCart({{ $product->id }}, "{{ $product->price }}", @json($product->name), @json($product->images->first()?->image_url ?? ""))' class="w-8 h-8 rounded-full bg-red-800 flex items-center justify-center text-white hover:bg-red-900 transition shadow-md" title="Add to Cart">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

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
    .product-card:hover .buttons-overlay {
        opacity: 1;
    }
    /* Fix for specificity */
    .product-card:hover > div > div:first-child {
        opacity: 1 !important;
    }
</style>

<script>
    function quickAddToCart(id, price, name, image) {
        // Dispatch event to be handled by Alpine store in layout
        window.dispatchEvent(new CustomEvent('add-to-cart', {
            detail: {
                id: id,
                name: name,
                price: price,
                image: image,
                quantity: 1
            }
        }));
    }

    function addToWishlist(productId) {
        fetch('/wishlist/add', {
            method: 'POST',
            body: JSON.stringify({ product_id: productId }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (typeof showNotification === 'function') {
                showNotification(data.message, 'success');
            } else {
                alert(data.message);
            }
        });
    }
</script>
@endsection

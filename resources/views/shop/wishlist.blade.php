@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">قائمتي المفضلة</h1>

    @if($products->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <h2 class="text-xl font-bold text-gray-800 mb-2">قائمتك المفضلة فارغة</h2>
            <p class="text-gray-500 mb-6">احفظ المنتجات التي تعجبك هنا للوصول إليها لاحقاً.</p>
            <a href="/shop" class="inline-block px-6 py-2 bg-[#8B3A3A] text-white rounded-lg hover:bg-[#722F37] transition">تصفح المتجر</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
            @foreach($products as $product)
                <div class="relative bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                    <button onclick="event.preventDefault(); removeFromWishlist({{ $product->id }}, this)" class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50 z-20">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <a href="/shop/product/{{ $product->slug }}" class="block relative aspect-square overflow-hidden">
                        @if($product->images->first())
                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #8B3A3A 0%, #722F37 100%);"></div>
                        @endif
                    </a>

                    <div style="padding: 1rem;">
                        <a href="/shop/product/{{ $product->slug }}" class="block mb-2" style="text-decoration: none; color: inherit;">
                            <h3 style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</h3>
                        </a>
                        
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <p style="color: #8B3A3A; font-weight: bold;">{{ number_format($product->price, 0) }} DA</p>
                                
                                <button onclick='event.preventDefault(); event.stopPropagation(); window.dispatchEvent(new CustomEvent("add-to-cart", { detail: { id: {{ $product->id }}, name: @json($product->name), price: "{{ $product->price }}", image: @json($product->images->first()?->image_url ?? "") } }))' class="w-8 h-8 rounded-full bg-red-800 flex items-center justify-center text-white hover:bg-red-900 transition shadow-md z-20 relative" title="Add to Cart">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function removeFromWishlist(productId, btn) {
    if(!confirm('هل أنت متأكد من إزالة هذا المنتج من المفضلة؟')) return;

    fetch('/wishlist/remove', {
        method: 'POST',
        body: JSON.stringify({ product_id: productId }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => {
        if(res.ok) {
            btn.closest('div').remove();
            if(document.querySelectorAll('.grid > div').length === 0) {
                location.reload();
            }
        }
    });
}
</script>
@endsection

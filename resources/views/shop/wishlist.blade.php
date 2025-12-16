@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-end justify-between mb-8">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-gray-900">Your Wishlist</h1>
        <span class="text-gray-500 font-sans text-sm">{{ $products->count() }} Items</span>
    </div>

    @if($products->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-3">Your wishlist is empty</h2>
            <p class="text-gray-500 mb-8 max-w-sm mx-auto">Save items you love here to easily find them later. Start exploring our collection today.</p>
            <a href="/shop" class="inline-flex items-center justify-center rounded-xl bg-[#8B3A3A] px-8 py-3 text-base font-medium text-white hover:bg-[#722F37] transition shadow-md hover:shadow-lg">
                Explore Shop
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">
                    <!-- Remove Button -->
                    <button onclick="removeFromWishlist({{ $product->id }}, this)" 
                        class="absolute top-3 right-3 p-2 rounded-full bg-white/90 backdrop-blur-sm text-gray-400 hover:text-red-500 hover:bg-red-50 transition z-10 shadow-sm"
                        title="Remove from Wishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <!-- Image -->
                    <a href="/shop/product/{{ $product->slug }}" class="aspect-square w-full bg-gray-200 relative overflow-hidden block">
                        @if($product->images->first())
                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform transition duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#8B3A3A] to-[#722F37] text-white">
                                <span class="font-serif">No Image</span>
                            </div>
                        @endif
                    </a>

                    <!-- Content -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 mb-1 line-clamp-1">
                                <a href="/shop/product/{{ $product->slug }}" class="hover:text-[#8B3A3A] transition">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50 mt-auto">
                            <p class="font-serif text-lg font-bold text-[#8B3A3A]">{{ number_format($product->price, 0) }} <span class="text-xs font-sans font-normal text-gray-500">DA</span></p>
                            
                            <!-- Add to Cart Button (Quick Add) -->
                            <button onclick='event.preventDefault(); event.stopPropagation(); addToCart(@json($product))' 
                                class="relative z-20 flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white hover:bg-[#8B3A3A] transition shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#8B3A3A] focus:ring-offset-2"
                                title="Add to Cart">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Remove Item</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to remove this item from your wishlist? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirm-delete-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Remove
                </button>
                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B3A3A] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let itemToDeleteId = null;
    let itemToDeleteBtn = null;
    const modal = document.getElementById('delete-modal');

    function removeFromWishlist(productId, btn) {
        itemToDeleteId = productId;
        itemToDeleteBtn = btn;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        itemToDeleteId = null;
        itemToDeleteBtn = null;
    }

    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (!itemToDeleteId) return;
        
        const btn = itemToDeleteBtn;
        const card = btn ? btn.closest('.group') : null;
        if(card) card.style.opacity = '0.5';

        fetch('/wishlist/remove', {
            method: 'POST',
            body: JSON.stringify({ product_id: itemToDeleteId }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => {
            if(res.ok) {
                closeModal();
                if(card) {
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.remove();
                        const remaining = document.querySelectorAll('.group').length;
                        if(remaining === 0) location.reload();
                    }, 200);
                } else {
                    location.reload();
                }
            } else {
                if(card) card.style.opacity = '1';
                alert('Failed to remove item');
                closeModal();
            }
        });
    });

    function addToCart(product) {
        window.dispatchEvent(new CustomEvent("add-to-cart", { 
            detail: { 
                id: product.id, 
                name: product.name, 
                price: product.price, 
                image: product.images && product.images.length > 0 ? product.images[0].image_url : null,
                quantity: 1
            } 
        }));
    }
</script>
@endsection

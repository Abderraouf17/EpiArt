@extends('layouts.app')

@section('content')
    @php
        $isAuth = auth()->check();
        $priceValue = (float) $product->price;
        $prodId = $product->id;
    @endphp

    <div id="product-data" data-auth="{{ $isAuth ? 'true' : 'false' }}" data-price="{{ $priceValue }}"
        data-product-id="{{ $prodId }}" class="hidden"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
            <!-- Image Gallery -->
            <div class="space-y-4">
                <div class="w-full max-h-[400px] md:max-h-none md:aspect-square rounded-2xl overflow-hidden bg-gray-100 relative group">
                    @if($product->images->first())
                        <img id="main-image" src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover transform transition duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#8B3A3A] to-[#722F37] text-white text-xl font-serif">
                            <span>No Image Available</span>
                        </div>
                    @endif
                    
                    <!-- Wishlist Button (Mobile/Desktop overlay) -->
                    <button id="wishlist-btn-overlay" class="absolute top-4 right-4 p-3 rounded-full bg-white/90 backdrop-blur-sm shadow-lg text-[#8B3A3A] hover:bg-[#8B3A3A] hover:text-white transition-all duration-300 group-hover:opacity-100 opacity-0 transform translate-y-2 group-hover:translate-y-0" title="Add to Wishlist">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>

                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-2 md:gap-4">
                        @foreach($product->images as $image)
                            <button onclick="document.getElementById('main-image').src = '{{ $image->image_url }}'" 
                                class="aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-[#8B3A3A] transition focus:outline-none focus:border-[#8B3A3A]">
                                <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <nav aria-label="Breadcrumb">
                    <ol role="list" class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
                        <li><a href="/" class="hover:text-[#8B3A3A] transition">Home</a></li>
                        <li>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </li>
                        <li><a href="/shop" class="hover:text-[#8B3A3A] transition">Shop</a></li>
                        <li>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </li>
                        <li class="font-medium text-gray-900 truncate">{{ $product->name }}</li>
                    </ol>
                </nav>

                <h1 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <div class="mt-3 md:mt-4 flex items-end gap-3 md:gap-4">
                    <p class="font-serif text-2xl md:text-3xl font-bold text-[#8B3A3A]">
                        <span id="total-price">{{ number_format((float) $product->price, 2) }}</span> <span class="text-lg text-gray-600 font-sans font-normal">DA</span>
                    </p>
                    @if($product->category)
                        <span class="mb-1 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                            {{ $product->category->name }}
                        </span>
                    @endif
                </div>

                <div class="mt-6 md:mt-8 prose prose-sm text-gray-600 font-light leading-relaxed text-sm md:text-base">
                    {{ $product->description }}
                </div>

                <form id="add-to-cart" class="mt-6 md:mt-8 border-t border-gray-200 pt-6 md:pt-8">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="price" id="price-input" value="{{ $product->price }}">

                    <!-- Variations -->
                    @if($product->variations->count() > 0)
                        @php
                            $types = $product->variations->pluck('type')->unique();
                        @endphp

                        @foreach($types as $type)
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-900 capitalize mb-3">{{ $type }}</h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($product->variations->where('type', $type) as $var)
                                        <label class="group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium uppercase sm:flex-1 cursor-pointer focus:outline-none hover:bg-gray-50 shadow-sm transition-all has-[:checked]:ring-2 has-[:checked]:ring-[#8B3A3A] has-[:checked]:border-[#8B3A3A] has-[:checked]:text-[#8B3A3A]">
                                            <input type="radio" 
                                                   name="variation_{{ $type }}" 
                                                   value="{{ $var->value }}" 
                                                   class="sr-only variation-input" 
                                                   data-type="{{ $type }}" 
                                                   data-price="{{ $var->additional_price }}"
                                                   {{ $loop->first ? 'checked' : '' }}
                                            >
                                            <span class="text-gray-900 group-has-[:checked]:font-bold">{{ $var->value }}</span>
                                            @if($var->additional_price > 0)
                                                <span class="ml-2 text-xs text-gray-500 group-has-[:checked]:text-[#8B3A3A]">+{{ $var->additional_price }} DA</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Hidden inputs for non-varied logical support if needed, mostly redundant but keeps form simple -->
                        <input type="hidden" name="quantity" value="1" disabled> <!-- Will be overridden by qty selector -->
                    @endif

                    <!-- Quantity & Buttons Grid -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden shadow-sm w-full sm:w-auto h-[52px]">
                            <button type="button" onclick="updateQty(-1)" class="px-4 h-full text-gray-600 hover:bg-gray-100 transition text-lg">-</button>
                            <input type="number" name="quantity" id="quantity-input" value="1" min="1" class="w-16 h-full border-none text-center text-gray-900 focus:ring-0 appearance-none bg-transparent font-medium" readonly>
                            <button type="button" onclick="updateQty(1)" class="px-4 h-full text-gray-600 hover:bg-gray-100 transition text-lg">+</button>
                        </div>

                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                            <button type="submit" class="w-full flex items-center justify-center rounded-xl bg-[#8B3A3A] px-6 md:px-8 py-3 text-sm md:text-base font-medium text-white hover:bg-[#722F37] focus:outline-none focus:ring-2 focus:ring-[#8B3A3A] focus:ring-offset-2 transition-all shadow-md hover:shadow-lg">
                                Add to Cart
                            </button>
                            <button type="button" class="buy-now-btn w-full flex items-center justify-center rounded-xl border-2 border-[#8B3A3A] bg-white px-6 md:px-8 py-3 text-sm md:text-base font-medium text-[#8B3A3A] hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#8B3A3A] focus:ring-offset-2 transition-all"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}"
                                data-product-image="{{ $product->images->first()?->image_url ?? '' }}">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>In Stock & Ready to Ship</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        <span>Secure Payment</span>
                    </div>
                </div>
            </div>
        </div>

        @if($relatedProducts->count() > 0)
            <div class="mt-16 md:mt-24 border-t border-gray-200 pt-8 md:pt-12">
                <h2 class="font-serif text-2xl md:text-3xl font-bold text-gray-900 mb-6 md:mb-8">Related Products</h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-y-10 md:gap-x-6 xl:gap-x-8">
                    @foreach($relatedProducts as $related)
                        <div class="group relative">
                            <div class="aspect-square w-full overflow-hidden rounded-xl bg-gray-200 group-hover:opacity-75 relative">
                                @if($related->images->first())
                                    <img src="{{ $related->images->first()->image_url }}" alt="{{ $related->name }}" class="h-full w-full object-cover object-center transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">No Image</div>
                                @endif
                                <!-- Quick Action Overlay -->
                                <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 transition duration-300 flex justify-center pb-6">
                                    <a href="/shop/product/{{ $related->slug }}" class="bg-white text-gray-900 px-6 py-2 rounded-full font-medium shadow-lg hover:bg-gray-50 transform translate-y-4 group-hover:translate-y-0 transition">View Details</a>
                                </div>
                            </div>
                            <div class="mt-3 md:mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm md:text-lg font-medium text-gray-900">
                                        <a href="/shop/product/{{ $related->slug }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $related->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $related->category->name }}</p>
                                </div>
                                <p class="text-lg font-medium text-[#8B3A3A]">{{ number_format($related->price, 0) }} DA</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script type="text/javascript">
        'use strict';
        const dataEl = document.getElementById('product-data');
        const isAuthenticated = dataEl.dataset.auth === 'true';
        const productPrice = parseFloat(dataEl.dataset.price);
        const productIdVal = parseInt(dataEl.dataset.productId);

        // Update quantity
        function updateQty(change) {
            const input = document.getElementById('quantity-input');
            let val = parseInt(input.value) || 1;
            val += change;
            if (val < 1) val = 1;
            input.value = val;
        }

        // Initialize price update
        document.addEventListener('DOMContentLoaded', updatePrice);

        // Listen for variation changes
        document.querySelectorAll('.variation-input').forEach(input => {
            input.addEventListener('change', updatePrice);
        });

        function updatePrice() {
            let total = productPrice;
            document.querySelectorAll('.variation-input:checked').forEach(input => {
                const add = parseFloat(input.dataset.price);
                if (!isNaN(add)) {
                    total += add;
                }
            });
            document.getElementById('total-price').textContent = total.toFixed(2);
            const priceInput = document.getElementById('price-input');
            if (priceInput) priceInput.value = total;
        }

        document.getElementById('add-to-cart').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const quantity = parseInt(document.getElementById('quantity-input').value) || 1;
            const productId = form.querySelector('input[name="product_id"]').value;
            const price = document.getElementById('price-input').value; // Price is updated by updatePrice()
            const name = '{{ $product->name }}';
            const image = '{{ $product->images->first()?->image_url ?? "" }}';

            // Find variations
            let variationType = null;
            let variationValue = null;

            const checkedVar = form.querySelector('.variation-input:checked');
            if (checkedVar) {
                variationType = checkedVar.dataset.type;
                variationValue = checkedVar.value;
            }

            // Dispatch global event
            window.dispatchEvent(new CustomEvent('add-to-cart', {
                detail: {
                    id: productId,
                    name: name,
                    price: price,
                    image: image,
                    quantity: quantity,
                    variation_type: variationType,
                    variation_value: variationValue
                }
            }));
        });

        // Wishlist button (Overlay)
        const wishlistBtn = document.getElementById('wishlist-btn-overlay');
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // prevent image click check
                addToWishlist(productIdVal);
            });
        }

        function addToWishlist(productId) {
            fetch('/wishlist/add', {
                method: 'POST',
                body: JSON.stringify({ product_id: productId }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    window.dispatchEvent(new CustomEvent('open-login-modal'));
                    return null;
                }
                return res.json();
            })
            .then(data => {
                if (data) {
                    window.dispatchEvent(new CustomEvent('show-notification', {
                        detail: {
                            message: data.message,
                            type: data.status === 'success' ? 'success' : 'error'
                        }
                    }));
                }
            });
        }

        // Buy Now Button
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('buy-now-btn')) {
                e.preventDefault();
                
                const form = e.target.closest('form');
                if (!form) return;

                const quantity = parseInt(document.getElementById('quantity-input').value) || 1;
                const productId = form.querySelector('input[name="product_id"]').value;
                const price = document.getElementById('price-input').value;
                const name = '{{ $product->name }}';
                const image = '{{ $product->images->first()?->image_url ?? "" }}';

                let variationType = null;
                let variationValue = null;
                
                const checkedVar = form.querySelector('.variation-input:checked');
                 if (checkedVar) {
                    variationType = checkedVar.dataset.type;
                    variationValue = checkedVar.value;
                }

                window.dispatchEvent(new CustomEvent('buy-now', { 
                    detail: {
                        id: productId,
                        name: name,
                        price: price,
                        image: image,
                        quantity: quantity,
                        variation_type: variationType,
                        variation_value: variationValue
                    } 
                }));
            }
        });
    </script>
@endsection
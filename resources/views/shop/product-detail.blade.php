@extends('layouts.app')

@section('content')
@php
    $isAuth = auth()->check();
    $priceValue = (float)$product->price;
    $prodId = $product->id;
@endphp

<div id="product-data" data-auth="{{ $isAuth ? 'true' : 'false' }}" data-price="{{ $priceValue }}" data-product-id="{{ $prodId }}" style="display:none;"></div>

<div style="max-width: 1000px; margin: 0 auto; padding: 2rem 1rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- Images -->
        <div>
            <div style="position: relative; aspect-ratio: 1; background: #f3f4f6; border-radius: 8px; overflow: hidden; margin-bottom: 1rem;">
                @if($product->images->first())
                    <img id="main-image" src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #8B3A3A 0%, #722F37 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">No Image</div>
                @endif
            </div>
            @if($product->images->count() > 1)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem;">
                    @foreach($product->images as $image)
                        <img src="{{ $image->image_url }}" alt="{{ $product->name }}" style="cursor: pointer; border-radius: 6px; border: 2px solid transparent; transition: all 0.3s;" onclick="document.getElementById('main-image').src = '{{ $image->image_url }}';">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Details -->
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 1rem;">{{ $product->name }}</h1>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">{{ $product->category->name }}</p>
            
            <div style="font-size: 2rem; font-weight: bold; color: #8B3A3A; margin-bottom: 1.5rem;">
                <span id="total-price">{{ number_format((float)$product->price, 2) }}</span> DA
            </div>

            @if($product->description)
                <div style="margin-bottom: 2rem; color: #374151; line-height: 1.6;">
                    {{ $product->description }}
                </div>
            @endif

            @if($product->variations->count() > 0)
                <form id="add-to-cart" style="margin-bottom: 2rem;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="price" id="price-input" value="{{ $product->price }}">

                    <div style="margin-bottom: 1.5rem;">
                        @php
                            $types = $product->variations->pluck('type')->unique();
                        @endphp
                        
                        @foreach($types as $type)
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; text-transform: capitalize;">{{ $type }}</label>
                                <select name="variation_type" class="variation-select" data-type="{{ $type }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                                    <option value="">اختر {{ $type }}</option>
                                    @foreach($product->variations->where('type', $type) as $var)
                                        <option value="{{ $var->value }}" data-price="{{ $var->additional_price }}">{{ $var->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">الكمية</label>
                        <input type="number" name="quantity" value="1" min="1" style="width: 100px; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                    </div>

                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #8B3A3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 1rem; margin-bottom: 0.75rem;">إضافة إلى السلة</button>
                    <button type="button" class="buy-now-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->price }}" data-product-image="{{ $product->images->first()?->image_url ?? '' }}" style="width: 100%; padding: 0.75rem; background: #722F37; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 1rem;">Buy Now</button>
                </form>
            @else
                <form id="add-to-cart" style="margin-bottom: 2rem;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="quantity" value="1">

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">الكمية</label>
                        <input type="number" name="quantity" value="1" min="1" style="width: 100px; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px;">
                    </div>

                    <button type="submit" style="width: 100%; padding: 0.75rem; background: #8B3A3A; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 1rem; margin-bottom: 0.75rem;">إضافة إلى السلة</button>
                    <button type="button" class="buy-now-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->price }}" data-product-image="{{ $product->images->first()?->image_url ?? '' }}" style="width: 100%; padding: 0.75rem; background: #722F37; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 1rem;">Buy Now</button>
                </form>
            @endif

            @if($isAuth)
                <button id="wishlist-btn" data-product-id="{{ $prodId }}" style="width: 100%; padding: 0.75rem; background: white; color: #8B3A3A; border: 2px solid #8B3A3A; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;">❤️ أضف إلى المفضلة</button>
            @endif
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div style="margin-top: 4rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 2rem;">منتجات ذات صلة</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @foreach($relatedProducts as $related)
                    <a href="/shop/product/{{ $related->slug }}" style="text-decoration: none; color: inherit;">
                        <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s;">
                            @if($related->images->first())
                                <img src="{{ $related->images->first()->image_url }}" alt="{{ $related->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                            @endif
                            <div style="padding: 1rem;">
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem;">{{ $related->name }}</h3>
                                <p style="color: #8B3A3A; font-weight: bold;">{{ number_format($related->price, 0) }} DA</p>
                            </div>
                        </div>
                    </a>
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
    
    // Wait for Alpine to be ready
    document.addEventListener('alpine:init', () => {
        console.log('Alpine initialized');
    });
    
    document.getElementById('add-to-cart').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!isAuthenticated) {
            window.location.href = '/login';
            return;
        }

        // Get form data
        const formData = new FormData(this);
        const productId = formData.get('product_id');
        const productName = '{{ $product->name }}';
        const productPrice = formData.get('price') || '{{ $product->price }}';
        const productImage = '{{ $product->images->first()?->image_url ?? "" }}';
        const quantity = parseInt(formData.get('quantity')) || 1;

        // Add to Alpine.js cart
        setTimeout(() => {
            const navElement = document.querySelector('nav[x-data]');
            if (navElement) {
                // Try Alpine.$data method
                if (window.Alpine && typeof Alpine.$data === 'function') {
                    const alpineData = Alpine.$data(navElement);
                    if (alpineData && alpineData.addToCart) {
                        const product = {
                            id: productId,
                            name: productName,
                            price: productPrice,
                            image: productImage
                        };
                        
                        for (let i = 0; i < quantity; i++) {
                            alpineData.addToCart(product);
                        }
                        return;
                    }
                }
                
                // Fallback: dispatch custom event
                window.dispatchEvent(new CustomEvent('add-to-cart', {
                    detail: {
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        quantity: quantity
                    }
                }));
            }
        }, 100);
    });

    document.querySelectorAll('.variation-select').forEach(select => {
        select.addEventListener('change', updatePrice);
    });

    function updatePrice() {
        let total = productPrice;
        document.querySelectorAll('.variation-select').forEach(select => {
            const selected = select.querySelector('option:checked');
            if (selected && selected.dataset.price) {
                total += parseFloat(selected.dataset.price);
            }
        });
        document.getElementById('total-price').textContent = total.toFixed(2);
        document.getElementById('price-input').value = total;
    }

    if (document.getElementById('wishlist-btn')) {
        document.getElementById('wishlist-btn').addEventListener('click', function() {
            addToWishlist(productIdVal);
        });
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
        .then(data => alert(data.message));
    }
    
    // Event delegation for Buy Now buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('buy-now-btn')) {
            e.preventDefault();
            
            if (!isAuthenticated) {
                window.location.href = '/login';
                return;
            }
            
            const btn = e.target;
            const id = btn.dataset.productId;
            const name = btn.dataset.productName;
            const price = btn.dataset.productPrice;
            const image = btn.dataset.productImage;
            
            // Add to Alpine.js cart
            setTimeout(() => {
                const navElement = document.querySelector('nav[x-data]');
                if (navElement) {
                    // Try Alpine.$data method
                    if (window.Alpine && typeof Alpine.$data === 'function') {
                        const alpineData = Alpine.$data(navElement);
                        if (alpineData && alpineData.addToCart) {
                            alpineData.addToCart({
                                id: id,
                                name: name,
                                price: price,
                                image: image
                            });
                            alpineData.cartOpen = true;
                            return;
                        }
                    }
                    
                    // Fallback: dispatch custom event
                    window.dispatchEvent(new CustomEvent('buy-now', {
                        detail: {
                            id: id,
                            name: name,
                            price: price,
                            image: image
                        }
                    }));
                }
            }, 100);
        }
    });
</script>
@endsection

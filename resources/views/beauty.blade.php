@extends('layouts.app')

@section('content')
    <!-- Beauty Hero Section -->
    <div class="relative h-screen overflow-hidden">
        <img src="/images/EpiArt-story.png" class="absolute inset-0 w-full h-full object-cover" alt="Beauty Hero">
        <div class="absolute inset-0 bg-black/30"></div>
        
        <div class="relative z-10 h-full flex flex-col justify-center items-start text-white px-8 md:px-16 pt-20 max-w-7xl mx-auto">
            <span class="uppercase tracking-[0.2em] text-sm mb-4 font-semibold text-pink-300">Pure & Organic</span>
            <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl mb-6 font-bold">Radiant Natural Beauty</h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10 leading-relaxed">
                Natural ingredients and traditional beauty products for your wellness journey.
            </p>
            <a href="/shop" class="px-8 py-4 bg-pink-600 text-white font-bold rounded hover:bg-pink-700 transition transform hover:scale-105">
                DISCOVER BEAUTY
            </a>
        </div>
    </div>

    <!-- Beauty Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        
        <!-- Section Title -->
        <div class="text-center mb-16">
            <h2 class="text-3xl font-serif font-bold text-purple-900 mb-4">Pure Beauty Collections</h2>
            <div class="h-1 w-20 bg-pink-500 mx-auto"></div>
        </div>

        <!-- Categories Grid -->
        <div class="flex justify-center mb-20">
            <div class="flex gap-4 w-full max-w-4xl px-4 h-96">
                
                <a href="/shop" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                    <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                        <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Skincare">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                        <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                            <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Skincare</h3>
                        </div>
                    </div>
                </a>

                <a href="/shop" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                    <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                        <img src="https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Haircare">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                        <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                            <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Haircare</h3>
                        </div>
                    </div>
                </a>

                <a href="/shop" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                    <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                        <img src="https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Aromatherapy">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                        <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                            <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Aromatherapy</h3>
                        </div>
                    </div>
                </a>

                <a href="/shop" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                    <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                        <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Cosmetics">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                        <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                            <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Cosmetics</h3>
                        </div>
                    </div>
                </a>

                <a href="/shop" class="group cursor-pointer flex-1 transition-all duration-1000 hover:flex-[2.5]">
                    <div class="relative w-full h-full overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-1000">
                        <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=1200&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt="Wellness">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-1000"></div>
                        <div class="absolute inset-0 flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                            <h3 class="text-2xl font-bold text-white uppercase tracking-wider">Wellness</h3>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Featured Products -->
        <div class="py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold text-purple-900 mb-4">Featured Beauty Products</h2>
                <div class="h-1 w-20 bg-pink-500 mx-auto"></div>
            </div>

            @if($featuredProducts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        <a href="/shop/product/{{ $product->slug }}" class="group">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-pink-50 to-purple-100 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-pink-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 right-3 bg-pink-600 text-white px-3 py-1 rounded-full text-xs font-semibold">Featured</div>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-pink-600 transition mb-2 truncate">{{ $product->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                                    <div class="flex justify-between items-center mt-3">
                                        <p class="text-pink-600 font-bold text-lg">{{ number_format($product->price, 0) }} DA</p>
                                        <div class="flex gap-2 z-20 relative">
                                            <button @click.prevent="addToWishlist({{ $product->id }})" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            </button>
                                            <button @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->images->first()?->image_url ?? '' }}' })" class="w-8 h-8 rounded-full bg-pink-600 flex items-center justify-center text-white hover:bg-pink-700 transition shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No featured products yet. Check back soon!</p>
                </div>
            @endif
        </div>

        <!-- New Arrivals -->
        <div class="py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-serif font-bold text-purple-900 mb-4">New Beauty Essentials</h2>
                <div class="h-1 w-20 bg-pink-500 mx-auto"></div>
            </div>

            @if($recentProducts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($recentProducts as $product)
                        <a href="/shop/product/{{ $product->slug }}" class="group">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                <div class="relative aspect-square overflow-hidden bg-gray-100">
                                    @if($product->images->first())
                                        <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-pink-50 to-purple-100 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-pink-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 right-3 bg-purple-500 text-white px-3 py-1 rounded-full text-xs font-semibold">New</div>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-pink-600 transition mb-2 truncate">{{ $product->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                                    <div class="flex justify-between items-center mt-3">
                                        <p class="text-pink-600 font-bold text-lg">{{ number_format($product->price, 0) }} DA</p>
                                        <div class="flex gap-2 z-20 relative">
                                            <button @click.prevent="addToWishlist({{ $product->id }})" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            </button>
                                            <button @click.prevent="addToCart({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $product->images->first()?->image_url ?? '' }}' })" class="w-8 h-8 rounded-full bg-pink-600 flex items-center justify-center text-white hover:bg-pink-700 transition shadow-md">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No new products yet. Check back soon!</p>
                </div>
            @endif
        </div>

    </div>
@endsection

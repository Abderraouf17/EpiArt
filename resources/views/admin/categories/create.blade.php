@extends('admin.layout')

@section('title', 'إنشاء فئة جديدة')
@section('header', 'إنشاء فئة جديدة')

@section('content')
<div class="card">
    <form method="POST" action="/admin/categories" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>اسم الفئة</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>صورة الفئة</label>
            <input type="file" name="image" accept="image/*" />
            <small>أو ادخل رابط الصورة</small>
        </div>

        <div class="form-group">
            <label>رابط الصورة</label>
            <input type="url" name="image_url" placeholder="https://...">
        </div>

        <div class="form-group">
            <label>المنتج المميز (اختياري)</label>
            <select name="featured_product_id">
                <option value="">لا يوجد</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @if(old('featured_product_id') == $product->id) selected @endif>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">إنشاء الفئة</button>
            <a href="/admin/categories" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

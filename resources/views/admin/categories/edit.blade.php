@extends('admin.layout')

@section('title', 'تعديل الفئة')
@section('header', 'تعديل الفئة')

@section('content')
<div class="card">
    <form method="POST" action="/admin/categories/{{ $category->id }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>اسم الفئة</label>
            <input type="text" name="name" value="{{ $category->name }}" required>
        </div>

        <div class="form-group">
            <label>الرقم</label>
            <input type="number" name="number" value="{{ $category->number }}">
        </div>

        <div class="form-group">
            <label>الوصف</label>
            <textarea name="description" rows="3">{{ $category->description }}</textarea>
        </div>

        @if($category->image_url)
            <div style="margin-bottom: 1rem;">
                <p>الصورة الحالية:</p>
                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" style="max-width: 200px; border-radius: 6px;">
            </div>
        @endif

        <div class="form-group">
            <label>رفع صورة الفئة</label>
            <input type="file" name="image" accept="image/*" />
        </div>

        <div class="form-group">
            <label>المنتج المميز</label>
            <select name="featured_product_id" style="background-position: left 0.5rem center; padding-right: 0.75rem; padding-left: 2.5rem; direction: rtl;">
                <option value="">لا يوجد</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @if($category->featured_product_id == $product->id) selected @endif>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="/admin/categories" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
